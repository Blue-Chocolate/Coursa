LearnForward is like an online school.

People join a course, watch lessons, finish them, and then get a certificate. But when I built it, I wasn’t just thinking about features — I was thinking about what could go wrong and how the product would grow later.

The biggest risk in any LMS is that people start a course and never finish it. If someone enrolls, watches one lesson, and disappears, that’s a failed experience. So I made sure the system tracks lesson progress at a very detailed level — it knows when you started and when you completed each lesson. That way we can see exactly where people drop off, not just that they dropped off.

Right now, though, there’s no automatic “come back” reminder if someone goes inactive for days. That’s something I’d add next.

Another big risk is certificate trust. If someone can get a certificate without actually completing the course, the whole platform loses credibility. So certificates are only generated after the system confirms every single lesson is completed. It’s an atomic process — meaning it’s safe even if two requests happen at the same time. Each certificate also has a UUID, so it’s not guessable.

What’s missing is a public verification page where an employer could check if a certificate is real. That would be a valuable addition.

Content quality and publishing flow is another area I thought about. If publishing courses is messy or slow, growth becomes limited. So I added draft and published states, soft deletes, and manual lesson ordering. That gives admins flexibility without touching code.

But there’s no approval workflow yet — like one admin reviewing before another publishes. That would matter if the team grows.

In terms of metrics, I’d focus on five main things.

First, course completion rate — how many enrolled users actually finish. That’s the core health metric.

Second, lesson drop-off point — where exactly users stop. Completion rate tells you something is wrong; drop-off tells you where it’s wrong.

Third, time to first lesson after enrollment. If someone enrolls but doesn’t start quickly, that suggests onboarding friction.

Fourth, certificate issuance rate — what percentage of enrolled users end up certified. That tells you if the platform is delivering on its promise.

And fifth, re-engagement after notifications. If we send a “new lesson added” notification, do people actually come back within 48 hours? If not, the notification isn’t effective.

For future evolution, the system is ready for paid courses. Enrollment already goes through a single service, so adding a payment check is straightforward. We’d just need a price column and a payments table.

For a mobile app, the service layer is already decoupled from the UI, so adding API controllers later wouldn’t require rewriting business logic. It’s extra work, but it’s bounded.

Corporate multi-tenancy would be the biggest refactor. We’d need tenants and tenant scoping across models and repositories. But because of the repository and service separation, that change would be contained mostly in the data layer.

Gamification is also easy to add because the system already uses events. We’d just introduce badge definitions and listeners reacting to things like lesson completion.

As for trade-offs, I intentionally chose a Livewire-only UI with no API. For a web-only product, building an API from day one felt like unnecessary overhead. The cost is that adding mobile later requires building the API layer, but the architecture supports it.

I also chose not to cache completion percentages yet. Caching introduces invalidation complexity, and showing incorrect progress is worse than running a slightly slower correct query. At the current scale, live calculation is fine.

And finally, I generate the certificate PDF at dispatch time instead of inside the queue worker. That avoids filesystem issues and makes the job self-contained. The trade-off is slightly higher request latency and larger job payloads, which is acceptable at current volume.

Overall, I wasn’t just building features. I was thinking about risks, metrics, scale, and future evolution — and making conscious trade-offs based on the current stage of the product.