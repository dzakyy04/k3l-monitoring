# 🎨 PROMPT: Full Visual Redesign K3L Monitoring

---

## Context

K3L Monitoring adalah aplikasi Laravel untuk monitoring absensi, APD, dan kegiatan petugas PLN. Stack: **Laravel Blade + Tailwind CSS v4 + DaisyUI v5**. App ini **mobile-first** (kebanyakan diakses dari HP petugas lapangan). Ada 2 role: `supervisor` dan `petugas`.

Redesign seluruh tampilan dengan estetika **modern, clean, Gen Z** mengikuti prinsip Taste Skill (anti-slop design).

---

## 🚫 ANTI-SLOP RULES (Taste Skill)

Jangan lakukan ini:

| ❌ Banned Pattern | ✅ Gantinya |
|---|---|
| Generic gradient hero (`bg-gradient-to-r from-blue-500 to-purple-600`) | Solid color dengan subtle texture atau pattern |
| Numbered eyebrow labels ("01 — Dashboard") | Simple uppercase label tanpa nomor |
| Random color splashes (tiap card beda warna) | **Satu accent color** konsisten end-to-end |
| Drop shadow besar (`shadow-2xl`) di setiap elemen | Gunakan `shadow-sm` saja, atau **borderless** dengan background contrast |
| Border di semua card | Pilih salah satu: border ATAU shadow, jangan dua-duanya |
| Terlalu banyak font weight berbeda | Maksimal 3: regular (400), semibold (600), extrabold (800) |
| Icon + text + badge + button dalam satu baris | Kurangi noise, pilih 2-3 elemen saja |
| Rounded yang tidak konsisten (mix rounded-lg, rounded-xl, rounded-2xl) | **Satu radius system**: semua pakai `rounded-2xl` (16px) |

---

## 🎯 DESIGN SYSTEM

### Color Palette
```
Primary:     oklch(0.65 0.18 230)     → Vibrant cyan-blue
Surface:     oklch(0.97 0.005 260)    → Warm off-white (bukan putih bersih)
Card:        oklch(1 0 0)             → Pure white card di atas off-white surface
Sidebar:     oklch(0.16 0.03 265)     → Near-black dengan hint blue
Text:        oklch(0.20 0.02 260)     → Soft black (bukan pure black #000)
Text muted:  oklch(0.55 0.02 260)     → Medium gray
Success:     oklch(0.72 0.19 155)     → Mint green
Warning:     oklch(0.80 0.16 80)      → Warm amber  
Error:       oklch(0.65 0.24 25)      → Coral red
```

**RULE: Satu accent color saja (cyan-blue). Jangan tambah warna lain untuk dekorasi.**

### Typography
```
Font:        Inter (Google Fonts)
Heading:     text-2xl font-extrabold tracking-tight (800 weight, tight spacing)
Subheading:  text-base font-bold (700 weight)  
Body:        text-sm font-medium (500 weight)
Caption:     text-xs font-semibold uppercase tracking-widest text-muted
```

**RULE: Heading SELALU pakai `tracking-tight`. Caption SELALU pakai `uppercase tracking-widest`.**

### Spacing & Shape
```
Card padding:    p-5 (mobile) / p-7 (desktop)
Section gap:     space-y-4
Card radius:     rounded-2xl (16px) — SEMUA card, input, button
Button radius:   rounded-xl (12px)
Badge radius:    rounded-full (pill shape)
```

**RULE: Tidak ada `rounded-lg` atau `rounded-md` di mana pun. Hanya `rounded-xl`, `rounded-2xl`, dan `rounded-full`.**

### Elevation
```
Card:        shadow-none, cukup bg-white di atas bg-base-200
Hover card:  translateY(-2px) + shadow-md (micro-lift)
Modal:       shadow-2xl + backdrop-blur-xl
Bottom nav:  backdrop-blur-xl + border-top tipis
```

**RULE: Default card TANPA shadow. Shadow hanya muncul saat hover (interaction feedback).**

---

## 📱 MOBILE-FIRST LAYOUT

### Bottom Navigation (Mobile)
```
- Fixed bottom, 68px height
- Frosted glass: bg-white/90 backdrop-blur-2xl
- 4-5 tab items, icon di atas, label di bawah
- Active state: icon + label berubah ke primary color
- Active dot indicator (4px circle) di bawah label
- Safe area padding untuk notch/home indicator
- Tap ripple animation saat ditekan
```

### Top Bar (Mobile)
```
- Sticky top, 56px height
- Frosted glass: bg-white/80 backdrop-blur-xl
- Logo kiri, theme toggle kanan
- Tidak ada hamburger menu (sudah ada bottom nav)
- Border-bottom yang sangat subtle (1px, 5% opacity)
```

### Sidebar (Desktop ≥1024px)
```
- Fixed left, 280px width
- Dark background (near-black)
- Logo + app name di atas
- Navigation links dengan rounded-xl active state
- Active = bg-primary, glow shadow (box-shadow primary/30%)
- User card di bottom dengan avatar initial, name, role
- Theme toggle di user card area
```

---

## 📄 PER-PAGE DESIGN SPEC

### Login Page
```
Layout:
- Split view: dark panel kiri (brand), white panel kanan (form)
- Mobile: full-width form, logo di atas

Brand Panel (kiri):
- Near-black background
- Logo PLN besar
- App name "K3L Monitoring" extrabold
- Tagline 1 baris, text-slate-400
- 3 mini feature cards di bottom (Absensi, APD, Laporan) — rounded-2xl, bg-white/8%

Form Panel (kanan):
- Eyebrow: "SELAMAT DATANG" (xs, uppercase, tracking-widest, primary color)
- Heading: "Login K3L Monitoring" (2xl, extrabold)
- Subtitle: 1 baris deskripsi (sm, muted)
- Form fields: DaisyUI `input input-bordered`, TIDAK ada label di atas
  (pakai floating label atau placeholder saja untuk clean look)
- "Ingat saya" checkbox + "Lupa password?" link sejajar
- Login button: full-width, `btn btn-primary`, rounded-xl
```

### Dashboard (Supervisor & Petugas)
```
Header Card:
- BUKAN hero gradient
- White card, eyebrow label "SUPERVISOR DASHBOARD" (primary color)
- Heading: "Ringkasan Monitoring K3L" (extrabold)
- Subtitle deskriptif
- 2 action buttons: primary + outline (btn-sm)
- Desktop: logo PLN di kanan dalam rounded-2xl dark container

Stat Cards (3 kolom):
- White card, TANPA border, TANPA shadow
- Hover: lift -2px + shadow-md (transition)
- Layout: value besar di kiri, icon container di kanan
- Icon container: 44px, rounded-xl, bg-primary/10 (10% opacity accent)
- Stat label: text-xs uppercase tracking-widest muted
- Stat value: text-3xl extrabold
- JANGAN pakai warna berbeda per stat card — semua putih, hanya icon color yang beda
```

### Data Table Pages (Absensi, Petugas, Lokasi Index)
```
Mobile:
- Card-based list (BUKAN table)
- Setiap item = white card, rounded-2xl
- Header row: nama + badge status di kanan
- Detail rows: 2-column grid, label muted, value bold
- Action buttons: ghost btn di bottom card

Desktop:
- Clean table, TANPA zebra stripe
- Header: uppercase, tracking-wider, text-xs, font-extrabold
- Row hover: bg-base-200/50 (sangat subtle)
- Status badge: pill shape (rounded-full), bold uppercase, xs
- Action buttons: ghost btn, text color sesuai aksi (warning/error)
- TIDAK ada border antar row — gunakan spacing/padding saja
```

### Form Pages (Create & Edit)
```
- Section cards: white, rounded-2xl, p-5
- Section heading: text-base extrabold
- Form control: DaisyUI `form-control`, label di atas (text-xs font-bold)
- Input: `input input-bordered input-sm`, full width
- Select: `select select-bordered select-sm`
- Textarea: `textarea textarea-bordered`, min 3 rows
- Checkbox APD: grid layout, setiap item dalam rounded-xl bordered container
  dengan hover:bg-base-200 transition
- File input: `file-input file-input-bordered file-input-sm`
- Action bar: flex, justify-end, gap-3
  - Cancel: `btn btn-ghost btn-sm`
  - Submit: `btn btn-primary btn-sm` dengan icon
```

### Absensi Create (Special — ada Map)
```
- 2-column layout di desktop (form kiri, map kanan)
- 1-column stacked di mobile
- Map container: rounded-2xl, border subtle, min-h-[400px]
- Geofence status alert: DaisyUI alert component
  - Inside area: alert-success
  - Outside area: alert-error  
  - Waiting GPS: alert-warning
- GPS refresh button: `btn btn-ghost btn-sm` dengan location icon
- Search: DaisyUI `join` component (input + button joined)
```

### Detail/Show Page
```
- 2-column grid di desktop
- Left: info card dengan foto di atas (DaisyUI figure)
- Right: map card
- APD items: pill badges (`badge badge-outline badge-sm`)
- Uraian: text-sm, leading-relaxed
- Actions: btn-outline + btn-primary di bottom
```

### Profile Page
```
- 2 section cards stacked:
  1. "Informasi Profil" — name + email fields
  2. "Update Password" — current + new + confirm fields
- Success message: inline text-success setelah button, bukan alert card
```

---

## ✨ MICRO-ANIMATIONS

```css
/* Card hover lift */
.card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px oklch(0 0 0 / 0.06); }

/* Page entrance */
main { animation: fadeIn 0.25s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } }

/* Bottom nav tap */
.btm-nav a:active { transform: scale(0.92); }

/* Button press */
.btn:active { transform: scale(0.97); }

/* Sidebar link hover */
.sidebar a { transition: all 0.15s ease; }
```

---

## 🌙 DARK MODE

```
Surface:     oklch(0.15 0.02 260)   → Deep dark blue-gray
Card:        oklch(0.20 0.03 260)   → Slightly lighter
Text:        oklch(0.92 0.01 260)   → Off-white
Text muted:  oklch(0.55 0.02 260)   → Medium gray
Border:      oklch(1 0 0 / 0.08)    → 8% white border
Primary:     tetap sama (cyan-blue)
```

**RULE: Dark mode BUKAN invert. Ini palette terpisah yang tetap terasa warm dan readable.**

---

## ⚡ IMPLEMENTATION NOTES

1. **DaisyUI theme**: Gunakan `@plugin "daisyui"` dengan custom theme colors
2. **Tailwind v4**: CSS-first config, `@theme` directive untuk custom values  
3. **Font loading**: `@import url(...)` di paling atas CSS
4. **Icons**: Inline SVG (Lucide icon set), stroke-width 1.8, 20x20px
5. **Leaflet maps**: Hanya style container-nya, JANGAN ubah JS logic
6. **PWA**: Pertahankan service worker registration dan manifest

---

## 🔑 SATU KALIMAT RINGKASAN

> Buat tampilan yang **calm, spacious, dan premium** — seperti app fintech modern (Wise, Linear, Notion) bukan seperti admin template Bootstrap. Setiap pixel harus punya purpose, tidak ada elemen dekoratif yang tidak berguna.
