# Learnforward — Architecture Documentation

This document describes the technical architecture, data flow, and design decisions behind the Learnforward platform.

---

## Table of Contents

- [High-Level Overview](#high-level-overview)
- [Directory Structure](#directory-structure)
- [Database Schema](#database-schema)
- [Core Domain Models](#core-domain-models)
- [Feature Flows](#feature-flows)
  - [Course Enrollment](#course-enrollment)
  - [Lesson Progress Tracking](#lesson-progress-tracking)
  - [Course Completion & Certificate Issuance](#course-completion--certificate-issuance)
  - [Certificate PDF Generation](#certificate-pdf-generation)
- [Events & Listeners](#events--listeners)
- [Jobs](#jobs)
- [Policies & Authorization](#policies--authorization)
- [Services](#services)
- [Repositories](#repositories)
- [Livewire Components](#livewire-components)
- [Mail & Queue Architecture](#mail--queue-architecture)
- [Admin Panel](#admin-panel)
- [Key Design Decisions](#key-design-decisions)

---

## High-Level Overview

```
┌─────────────────────────────────────────────────────┐
│                     Browser                         │
│         (Blade Views + Livewire Components)         │
└────────────────────┬────────────────────────────────┘
                     │ HTTP
┌────────────────────▼────────────────────────────────┐
│                Laravel Application                  │
│                                                     │
│   Routes → Controllers → Services → Models         │
│                                                     │
│   ┌─────────────┐        ┌──────────────────────┐  │
│   │ Filament    │        │  Queue Worker         │  │
│   │ Admin Panel │        │  (Mail + PDF Jobs)    │  │
│   └─────────────┘        └──────────────────────┘  │
└──────────┬──────────────────────────┬───────────────┘
           │                          │
┌──────────▼──────┐        ┌──────────▼──────────────┐
│   MySQL DB      │        │   SMTP Mail Server      │
└─────────────────┘        └─────────────────────────┘
```

---

## Directory Structure

```
app/
├── Actions/
│   ├── Auth/
│   │   └── RegisterUserAction.php
│   ├── Courses/
│   │   └── CheckCourseCompletionAction.php
│   ├── Enrollment/
│   │   └── EnrollUserAction.php
│   └── Lesson/
│       ├── MarkLessonCompletedAction.php
│       └── RecordLessonStartedAction.php
├── Console/                        # Scheduled commands
├── Events/
│   └── NewLessonAdded.php
├── Http/
│   ├── Controllers/                # Route handlers
│   └── Middleware/                 # Auth, role checks
├── Jobs/
│   └── CheckUserCourseCompletionJob.php
├── Livewire/
│   ├── Auth/
│   │   ├── Login.php
│   │   └── Register.php
│   └── Course/
│       ├── CourseDetail.php
│       ├── EnrollButton.php
│       └── MyCourses.php
├── Mail/
│   ├── CourseCompletionMail.php    # Queued mailable with PDF attachment
│   └── WelcomeMail.php
├── Models/
│   ├── Certificate.php
│   ├── Course.php
│   ├── CourseCompletion.php
│   ├── Enrollment.php
│   ├── Lesson.php
│   ├── LessonProgress.php
│   ├── Level.php
│   └── User.php
├── Notifications/
│   └── NewLessonNotification.php
├── Policies/
│   ├── CoursePolicy.php
│   └── LessonPolicy.php
├── Repositories/
│   ├── Contracts/
│   │   ├── CourseRepositoryInterface.php
│   │   ├── EnrollmentRepositoryInterface.php
│   │   ├── LessonRepositoryInterface.php
│   │   └── ProgressRepositoryInterface.php
│   ├── CourseRepository.php
│   ├── EnrollmentRepository.php
│   └── ProgressRepository.php
├── Services/
│   ├── AuthService.php
│   ├── CertificateService.php      # PDF generation logic
│   ├── CourseService.php
│   ├── EnrollmentService.php
│   ├── LessonService.php
│   └── ProgressService.php
├── Filament/
│   └── Resources/                  # Admin panel resources
└── Providers/

resources/
├── views/
│   ├── certificates/
│   │   └── certificate.blade.php   # Certificate PDF template
│   ├── emails/
│   │   ├── course-completion.blade.php
│   │   └── welcome.blade.php
│   ├── livewire/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   └── course/
│   │       ├── course-detail.blade.php
│   │       ├── enroll-button.blade.php
│   │       └── my-courses.blade.php
│   └── pages/

database/
├── migrations/
└── seeders/
```

---

## Database Schema

```
users
├── id
├── name
├── email
├── password
└── timestamps

levels
├── id
├── name
└── timestamps

courses
├── id
├── level_id          → levels.id
├── title
├── slug              (unique, withTrashed)
├── description
├── image
├── status            (draft | published)
├── deleted_at        (soft deletes)
└── timestamps

lessons
├── id
├── course_id         → courses.id
├── title
├── content
├── order
└── timestamps

enrollments
├── id
├── user_id           → users.id
├── course_id         → courses.id
└── timestamps

lesson_progress
├── id
├── user_id           → users.id
├── lesson_id         → lessons.id
├── completed_at
└── timestamps

course_completions
├── id
├── user_id           → users.id
├── course_id         → courses.id
└── timestamps

certificates
├── id
├── user_id           → users.id
├── course_id         → courses.id
├── uuid              (used in PDF filename and public URL)
├── issued_at
└── timestamps
```

---

## Core Domain Models

### Course
- Belongs to a `Level`
- Has many `Lesson`, `Enrollment`, `CourseCompletion`, `Certificate`
- Uses **soft deletes** — deleted courses are hidden from users but retained in the database
- Slugs are auto-generated on create/update and checked **withTrashed** to prevent collisions after restore
- `completionPercentageFor(User $user)` computes live progress without caching

### Lesson
- Belongs to a `Course`
- Ordered by `order` column
- Progress tracked via `LessonProgress`

### Enrollment
- Junction between `User` and `Course`
- Created when a user enrolls

### LessonProgress
- Records when a user completes a specific lesson (`completed_at`)
- Used to calculate overall course completion percentage

### CourseCompletion
- Created once when a user completes 100% of lessons in a course
- Triggers certificate issuance and completion email

### Certificate
- Created alongside `CourseCompletion`
- Has a `uuid` used for public verification URLs and PDF filenames
- Belongs to both `User` and `Course`

---

## Feature Flows

### Course Enrollment

```
User clicks "Enroll"
    → EnrollmentController@store
    → Enrollment::create([user_id, course_id])
    → Redirect to first lesson
```

### Lesson Progress Tracking

```
User marks lesson complete
    → LessonProgressController@store
    → LessonProgress::updateOrCreate([user_id, lesson_id], [completed_at => now()])
    → Check if all lessons complete
        → If yes → trigger Course Completion flow
```

### Course Completion & Certificate Issuance

```
All lessons completed
    → CourseCompletion::create([user_id, course_id])
    → Certificate::create([user_id, course_id, uuid, issued_at])
    → Dispatch CourseCompletionMail to queue
```

### Certificate PDF Generation

```
CourseCompletionMail constructed
    → CertificateService::generatePdf(user, course, certificate)
        → Pdf::loadView('certificates.certificate', [...])
        → setPaper('a4', 'landscape')
        → ->output()  ← returns raw binary string
    → base64_encode(pdfOutput) stored in job payload
    → Job serialized to queue

Queue worker processes job
    → Attachment::fromData(fn() => base64_decode($this->pdfBase64), ...)
    → Email sent with PDF attached
    → No temporary files written to disk
```

---

## Events & Listeners

### NewLessonAdded

**Fired when:** A new lesson is added to a course.

**Payload:** The `Lesson` model instance (read-only via `readonly`).

**Listener behavior:** Notifies all users enrolled in the course via `NewLessonNotification`, delivered over both `mail` and `database` channels.

```
Lesson created in admin panel
    → NewLessonAdded event dispatched
    → Listener fetches all enrollments for lesson->course_id
    → Each enrolled user receives NewLessonNotification
        → database: stores lesson_id, course_id, url in notifications table
        → mail: sends "New lesson added" email with link to lesson
```

### NewLessonNotification

Delivered via two channels:

| Channel | Details |
|---|---|
| `mail` | Subject: "New lesson added: {title}". Includes course name, lesson name, and a direct link. |
| `database` | Stores `lesson_id`, `lesson_title`, `course_id`, `course_title`, `course_slug`, `url` in the `notifications` table for in-app display. |

---

## Jobs

### CheckUserCourseCompletionJob

**Purpose:** Checks whether a user has completed all lessons in a course after marking a lesson complete. Runs asynchronously to avoid blocking the HTTP request.

**Dispatched by:** `MarkLessonCompletedAction` (after recording lesson completion).

**Handles:**
- Fetches the `User` and `Course` by stored IDs (not model instances, to avoid serialization issues)
- Delegates to `CheckCourseCompletionAction::execute()`
- If all lessons are complete: creates `CourseCompletion`, issues `Certificate`, dispatches `CourseCompletionMail`

**Retry config:**

| Setting | Value |
|---|---|
| `$tries` | 3 |
| `$backoff` | 5 seconds |

**Safety:** If the user or course no longer exists when the job runs, it exits silently without throwing.

```
MarkLessonCompletedAction
    → LessonProgress marked completed
    → CheckUserCourseCompletionJob::dispatch(userId, courseId)

Queue worker picks up job
    → User::find / Course::find
    → CheckCourseCompletionAction::execute(user, course)
        → Count total lessons vs completed lessons
        → If 100%:
            → insertCompletionOrIgnore() ← atomic, prevents duplicates
            → Certificate::create(...)
            → CourseCompletionMail::dispatch(...)
```

---

## Policies & Authorization

### CoursePolicy

| Method | Rule |
|---|---|
| `view(?User, Course)` | Published courses are public. Draft courses are only visible to enrolled users. Guests always see 404 for drafts. |
| `enroll(User, Course)` | Only authenticated users can enroll. Course must be published. |

Draft courses return `404` (not `403`) intentionally — this avoids leaking the existence of unpublished content.

### LessonPolicy

| Method | Rule |
|---|---|
| `view(?User, Lesson)` | Free preview lessons are public. All other lessons require authentication and enrollment. |
| `complete(User, Lesson)` | Only enrolled users can mark a lesson complete. |

Both policies accept nullable `?User` on `view` to support guest access for free previews without requiring a login check upstream.

---

## Services

Services contain business logic and orchestrate between repositories, actions, and models. Controllers and Livewire components call services — never repositories directly.

### AuthService
Handles registration, login, and logout. Delegates user creation to `RegisterUserAction` and manages Sanctum API token lifecycle.

### CourseService
- `listPublished(filters)` — filtered, paginated published course listing
- `findBySlug(slug, ?user)` — loads a course by slug, enforces draft visibility rules (404 for unauthorized access to drafts)
- `enrolledCourses(user)` — paginated list of courses a user is enrolled in

### EnrollmentService
- `enroll(user, course)` — idempotent enrollment. Returns existing enrollment silently if already enrolled. Delegates to `EnrollUserAction`.

### LessonService
- `getLesson(course, lessonId, ?user)` — loads a lesson, runs policy check, returns lesson + next/previous + progress state

### ProgressService
- `start(user, lesson)` — records lesson started, guards enrollment
- `complete(user, lesson)` — marks lesson complete, guards enrollment
- `updateWatchTime(user, lesson, seconds)` — updates watch seconds (only moves forward, never regresses)

### CertificateService
- `generatePdf(user, course, certificate)` — renders the certificate Blade view and returns raw PDF binary via DomPDF. No files written to disk.

---

## Repositories

Repositories abstract all database queries. Services depend on repository **interfaces**, not concrete implementations. This allows implementations to be swapped (e.g. for testing) via the service container.

### Interfaces

**CourseRepositoryInterface**
- `allPublished(filters)` — paginated published courses with optional level/search filters
- `findBySlug(slug)` — single course by slug
- `enrolledByUser(userId)` — paginated courses a user is enrolled in

**EnrollmentRepositoryInterface**
- `find(userId, courseId)` — single enrollment lookup
- `create(userId, courseId)` — idempotent creation via `firstOrCreate`
- `isEnrolled(userId, courseId)` — boolean check
- `findByUserAndCourse(userId, courseId)` — alias for `find`

**LessonRepositoryInterface**
- `findInCourse(lessonId, courseId)` — validates lesson belongs to the expected course
- `orderedByCourse(courseId)` — all lessons for a course in display order
- `allCompletedByUser(courseId, userId)` — completed lesson records for progress display

**ProgressRepositoryInterface**
- `find(userId, lessonId)` — single progress record
- `upsertStarted(userId, lessonId)` — creates progress row if not exists
- `markCompleted(userId, lessonId)` — **atomic** update using `WHERE completed_at IS NULL` guard to prevent duplicate completions under concurrent requests
- `updateWatchSeconds(userId, lessonId, seconds)` — **atomic** update using `WHERE watch_seconds < ?` to prevent regression
- `completedLessonIds(userId, courseId)` — collection of completed lesson IDs for a course
- `insertCompletionOrIgnore(userId, courseId)` — **atomic** `INSERT OR IGNORE` into `course_completions` — returns `true` only on first insert, preventing duplicate certificate issuance

---

## Livewire Components

All frontend interactivity is handled via Livewire. There are no separate API endpoints for the UI.

### Auth

**Login** (`livewire.auth.login`) — handles credential validation, session regeneration, and redirect on success. Dispatches `login-failed` browser event on failure for frontend feedback.

**Register** (`livewire.auth.register`) — validates name/email/password, delegates to `RegisterUserAction`, logs in the new user, and redirects home.

### Course

**CourseDetail** (`livewire.course.course-detail`) — displays a single course. Loads lessons in order, checks enrollment status, and computes completion percentage and completed lesson IDs live for the authenticated user.

**EnrollButton** (`livewire.course.enroll-button`) — isolated enrollment component. Redirects guests to login. On enroll, calls `EnrollUserAction` and dispatches `enrolled` browser event for parent component reactivity.

**MyCourses** (`pages.my-courses`) — dashboard view of all enrolled courses. For each enrollment, computes: completion percentage, completed lesson count, total lessons, whether the course is fully completed, and the next incomplete lesson to resume.

---

## Mail & Queue Architecture

Learnforward uses **Laravel's database queue driver** to send course completion emails asynchronously.

### Why async?
PDF generation is CPU-intensive. Running it synchronously during a web request would cause noticeable delays and risk HTTP timeouts.

### Why base64 in the payload?
The PDF binary cannot be JSON-serialized directly (malformed UTF-8). The solution is to base64-encode the raw PDF output and store it in the job payload, then decode it at send time inside `Attachment::fromData()`.

This avoids:
- Writing temporary files to disk (file not found errors across queue workers)
- Filesystem permission issues on shared hosting
- Stale file cleanup logic

### Job lifecycle

```
Request → new CourseCompletionMail(...)
            → PDF generated immediately in constructor
            → base64-encoded into $pdfBase64
            → Job serialized & pushed to jobs table

Queue Worker → unserializes job
             → calls attachments()
             → base64_decode($pdfBase64)
             → Attachment::fromData(...)
             → Email dispatched via SMTP
```

---

## Admin Panel

Built with **Filament**. Accessible at `/admin`.

Manages:
- Courses (create, edit, publish/draft, soft delete)
- Lessons (ordered per course)
- Levels
- Users
- Enrollments
- Certificates

Admin users are seeded or created manually via `php artisan tinker`.

---

## Key Design Decisions

| Decision | Rationale |
|---|---|
| DomPDF over Browsershot | Shared hosting has no Node.js or Puppeteer. DomPDF is pure PHP and works anywhere. |
| PDF in queue payload (base64) | Avoids filesystem race conditions between job dispatch and job execution on shared hosting. |
| Soft deletes on Course | Allows course recovery without breaking enrollment and completion history. |
| Slug uniqueness withTrashed | Prevents URL collisions if a soft-deleted course is restored with the same title. |
| Live completion percentage | No caching — always accurate. Acceptable for current scale; can be cached later if needed. |
| UUID on Certificate | Enables public verification URLs that are unguessable and not tied to internal IDs. |
| Database queue driver | No Redis available on Hostinger shared hosting. Database queue is sufficient for current load. |
| Repository pattern with interfaces | Decouples business logic from query implementation. Services depend on interfaces, enabling easy test fakes and future driver swaps. |
| Atomic progress updates | `markCompleted` uses `WHERE completed_at IS NULL` and `insertCompletionOrIgnore` uses `INSERT OR IGNORE` to prevent duplicate completions and certificates under concurrent requests. |
| Jobs store IDs not models | `CheckUserCourseCompletionJob` stores `userId` and `courseId` as integers rather than serialized Eloquent models to avoid stale model issues when the job is dequeued. |
| Draft courses return 404 not 403 | Prevents leaking the existence of unpublished content to unauthenticated or unenrolled users. |
| Free preview lessons are policy-level | Guest access to free lessons is enforced in `LessonPolicy` rather than middleware, keeping the rule close to the resource it protects. |
| Livewire over API | All UI interactivity is handled via Livewire components. No separate JSON API layer is needed for the current feature set, reducing surface area and complexity. |