Make-a-Wish — Thadingyut Lanterns (မြန်မာဘာသာ)

မင်္ဂလာပါ 🎐

ဒီ project (Thadingyut-Wish) ဟာ သင့်ကို သတိရစေမယ့် အလင်းရောင်တောက်ပတဲ့ Three.js နယူးဇာတ်လမ်းတစ်ပုဒ်ပါ။ သင့်ရဲ့ "ဆုတောင်း" မက်ဆေ့ခ်ျတွေကို ဝက်ဘ် UI မှတဆင့်တင်လို့ ဒီညညွှန်ပုံမှာ နတ်ဖုရားလို လင်းလင်းထွက်လာတဲ့ မီးပုံလုံး (lantern)တွေထဲက တစ်ခုအဖြစ် ပြပါလိမ့်မယ်။

ဒီ README မှာ အလွယ်တကူ စတင်အသုံးပြုနည်း၊ လိုအပ်ချက်နဲ့ ထိန်းချုပ်ရမည့်အချက်တွေကို မြန်မာလို သိသာရှင်းလင်းစွာ ရေးထားပါတယ်။

အတိုချုံး မိတ်ဆက်

- Frontend: `index.php` — Single-page app with Three.js scene, modal UI to submit wishes.
- Backend API: `api.php` — GET to fetch wishes, POST to save a new wish into MySQL using PDO.

လိုအပ်ချက် (Requirements)

- PHP (7.4+) နှင့် PDO MySQL extension
- MySQL database (schema အောက်မှာဖော်ပြထားပါတယ်)
- Web server (Apache / Nginx) သို့ local PHP built-in server နဲ့လည်း စမ်းနိုင်သည်
- Modern browser that supports WebGL (Chrome/Firefox/etc.)

စတင်အသုံးပြုရန် (Quick start)

1. Database ပြင်ဆင်ခြင်း

- `wishes` table ကို ဖန်တီးပါ။ ဒီ SQL ကို အသုံးပြုနိုင်ပါတယ်:

```sql
CREATE TABLE IF NOT EXISTS wishes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  wish_message TEXT NOT NULL,
  author_name VARCHAR(255) DEFAULT 'Anonymous',
  color_code INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

2. `api.php` မှာ DB ချိတ်ဆက်ဖို့ credential တွေကို သင့် server ပတ်ဝန်းကျင်အတိုင်း ပြောင်းပါ။

   - သတိပြုရမည့်အချက်: project ထဲမှာ DB password က commit မဟုတ်အောင် `.gitignore` သို့ config file ထည့်ပါ။

3. Local မှာ စမ်းရန် (PHP built-in server)

```bash
# project root မှာ
php -S 0.0.0.0:8000
# Browser မှာ http://localhost:8000/index.php ကို ဖွင့်ပါ
```

အသုံးပြုနည်း (How to use)

- UI ရဲ့ "ဆုတောင်း စာထည့်မယ်" ခလုပ်ကိုနှိပ်ပြီး ဆုတောင်းစာရေးပါ။
- အရောင်ရွေးချယ်ပြီး နာမည်ထည့်ပါ (သို့မဟုတ် အမည်မဖော်လိုပါဆိုရင် anonymous စနစ်ကို အမှန်ထားနိုင်သည်။)
- "ဆုတောင်းလွတ်တင်မယ်" ကိုနှိပ်ပါ — API ကို POST လုပ်ပြီး DB ထဲသွင်းပါမယ်။
- ထည့်သွင်းပြီးလာတဲ့ မီးပုံလုံးကို သင့် screen ပေါ်မှာ လင်းကျပ်တက်သွားတာကို တွေ့ရပါလိမ့်မယ်။
- မီးပုံလုံးကို ယုံကြည်စိတ်ချမှုနဲ့ နှိပ်ရင် သင်ရဲ့ဆုစာရင်နှင့် အမည်ကို ပြပါလိမ့်မယ်။

စိတ်ရှုပ်မှုရှင်းလင်းရေး (Notes & Safety)

- `api.php` မှာ အမှားအသေးစိတ်ကို client ကို ပြခွင့်မသင့်ပါ (production အတွက် error details ကို server-side မှာ log ထားပြီး user ကို general message ပဲ ပြပါ။)
- DB credentials ကို public repo ထဲ မထည့်ပါနဲ့။
- `wish_message` အရွယ်အစားကို တားမြစ်သင့်သည် (ဥပမာ 1000 characters) — server-side မှာ validate လုပ်ပါ။

မြန်ဆန်ချောမွေ့စေဖို့ အကြံပြုချက်များ

- Performance: အကယ်၍ lag ဖြစ်ပါက `MAX_LANTERNS` ကို ကျဆင်းစေပါ၊ flame particle ရေကို လျှော့ပါ၊ သို့မဟုတ် instanced meshes သို့ပြောင်းပါ။
- မူလ API response order ကို client-side မှာ `initialWishes.slice(-MAX_LANTERNS)` ကဲ့သို့ သုံး၍ နောက်ဆုံးရောက်ရှိသော အရာများကို ဖော်ပြပါ။

ကူညီလိုပါက

သင်ပြုလုပ်ချင်တဲ့ အလုပ်ဆောင်ချက်တစ်ခု ရှိရင် ပြောပါ — ဥပမာ:

- DB credentials ကို `.env` သို့ရွှေ့ရန်ကူညီပါမလား
- README ကို English/မြန်မာ နှစ်ဘာသာပြန်ပေးပါမလား
- Performance optimization (instancing) ကို implement ပေးပါမလား

ကျေးဇူးတင်ပါတယ်၊ သင့်ရဲ့ "ဆုတောင်း" တွေကို ဒီ project နဲ့ အလှတရားအဖြစ် ပြသပေးနိုင်ပါစေ 🌕✨
