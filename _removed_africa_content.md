# Removed Africa / Uganda Content
> Saved on 2026-05-25 per branding direction memo.
> All content below was removed from the public-facing website.
> Uganda/Africa content remains active on: the Donation page and the Johnny Davis Ministry landing page.

---

## 1. news.blade.php — Hero fallback description (line 70)
```
"Every meal served, every child helped, and every life changed has a story.
Follow the journey of communities in the Philippines and Uganda as hope becomes reality."
```

---

## 2. news.blade.php — Filter button (lines 190–192)
```html
<button class="filter-btn" data-filter="uganda">
  <span>Uganda <span class="filter-count">1</span></span>
</button>
```

---

## 3. news.blade.php — Mission Stats stat description (line 334)
```
Children fed monthly across Cebu and Uganda
```

---

## 4. news.blade.php — Mission Locations section subtitle (lines 368–369)
```
Our work spans two countries, touching lives in communities that need hope the most.
```

---

## 5. news.blade.php — Uganda Location Card (lines 393–412)
```html
<div class="location-card uganda reveal delay-2">
  <div class="location-image">
    <img src="{{ asset('images/landingpage/clean_drink_water.webp') }}"
         alt="Uganda mission work in Mbale" loading="lazy"/>
    <div class="location-overlay"></div>
    <div class="location-flag">🇺🇬</div>
  </div>
  <div class="location-content">
    <h3 class="location-title">Uganda</h3>
    <div class="location-areas">
      <span class="location-area">Mbale</span>
    </div>
    <p class="location-desc">
      Bringing clean water through well drilling and community development initiatives in Mbale region.
    </p>
    <button class="location-btn" data-location="uganda">
      View Uganda Stories
    </button>
  </div>
</div>
```

---

## 6. news.blade.php — Trending Strip Uganda item (lines 439–444)
```html
<div class="trending-item" role="button" tabindex="0" aria-label="Read: Well #7 Complete">
  <span class="trending-num" aria-hidden="true">02</span>
  <div class="trending-info">
    <p class="trending-cat">Field Report · Uganda</p>
    <p class="trending-title">Well #7 is Complete — 200 Families Now Have Clean Water</p>
  </div>
</div>
```

---

## 7. news.blade.php — Newsletter section Uganda references (lines 481, 486)
```
"Receive real mission reports directly from Cebu and Uganda. No spam. Just impact."
...
<span>Field reports from Cebu & Uganda, every month</span>
```

---

## 8. news.blade.php — Uganda Testimonial (lines 583–593)
```html
<div class="testimonial-card reveal delay-3">
  <div class="testimonial-quote">
    "Clean water changed everything for our village. No more walking miles to find water,
     no more children getting sick. This well is a miracle from God."
  </div>
  <div class="testimonial-author">
    <div class="author-avatar">👨‍👩‍👧‍👦</div>
    <div class="author-info">
      <div class="author-name">James Okello</div>
      <div class="author-title">Village Elder, Mbale, Uganda</div>
    </div>
  </div>
</div>
```

---

## 9. news.blade.php — Footer tagline (line 641)
```
Serving communities in the Philippines and Uganda through food, water, medical care, and education.
```

---

## 10. contact.blade.php — Footer tagline (line 231)
```
Serving communities in the Philippines and Uganda through food, water, medical care, and education.
```

---

## 11. partials/footer-donation.blade.php — Footer tagline (line 6)
```
Serving communities in the Philippines and Uganda.
```

---

## 12. database/seeders/ContentSeeder.php — News page meta description (line 133)
```
'Blog & News — Johnny Davis Global Missions. Monthly field updates, impact stories,
 and mission reports from the Philippines and Uganda.'
```

---

## 13. database/seeders/ContentSeeder.php — News hero description (line 141)
```
'Every meal served, every child helped, and every life changed has a story.
 Follow the journey of communities in the Philippines and Uganda as hope becomes reality.'
```

---

## 14. app/Http/Controllers/NewsController.php — Uganda post in getPosts()
```php
[
    'featured'   => false,
    'categories' => 'field-reports impact-stories',
    'country'    => 'Uganda',
    'flag'       => '🇺🇬',
    'image'      => 'images/landingpage/clean_drink_water.webp',
    'img_alt'    => 'Clean water flowing from a newly completed well in Uganda',
    'category'   => 'Field Report',
    'cat_class'  => 'cat-field',
    'date'       => 'February 28, 2025',
    'read_time'  => '4 min read',
    'title'      => 'Well #7 is Complete — 200 Families Now Have Clean Water',
    'excerpt'    => "After four months of drilling and community preparation, Well #7 in Mbale, Uganda was activated...",
    'cta_label'  => 'Read the Story',
    'cta_href'   => '#',
    'delay'      => 'delay-1',
],
```

## 15. app/Http/Controllers/NewsController.php — meta description fallback (line 22)
```php
'Blog & News — Johnny Davis Global Missions. Monthly field updates, impact stories, and mission reports from the Philippines and Uganda.'
```

---

## Notes
- `partials/donation-form.blade.php` lines 16 & 359 — Uganda references **kept** (donation page).
- `database/seeders/CampaignSeeder.php` line 50 — Uganda Water Wells campaign **kept** (donation-related).
- `database/migrations/2026_04_08_064409_create_donations_table.php` — comment mentioning Uganda Water Wells **kept** (migration comment only).
- Johnny Davis Ministry landing page — **untouched** per directive.
