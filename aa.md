# Magento 2 Lookbook PDF Modülü – AI Prompt

## 🌟 Amaç
Magento 2 için bir modül oluştur. Amaç, admin panelden PDF dosyalarını yüklemek ve frontend'de bunları lookbook tarzında, flipbook görünümüyle listelemek.

---

## 🔧 1. Admin Panel Prompt (PDF Yönetimi)

Magento 2 için bir admin panel arayüzü oluşur. Yeni bir menü ekle: "Lookbook PDFs". Bu menüde PDF dosyası yüklenebilecek bir form olsun. Her PDF için şu alanlar olsun:

- Başlık (title)
- Açıklama (description)
- PDF dosyası (media upload)
- Kapak görseli (optional image upload)
- Aktif/Pasif durumu (boolean)

Veriler Magento'nun standart custom model/repository yapısı ile kaydedilsin.

---

## 💻 2. Frontend Prompt (Listeleme & Lookbook UI)

Magento 2 frontend'ine özel bir route ve layout XML ekle: `/lookbook`.

Bu sayfa, admin panelde yüklenen aktif PDF’leri grid şeklinde listelesin. Her öğede:

- Kapak görseli gösterilsin
- Başlık altında “İncele” butonu yer alsın
- Hover efekti ile karartma ve buton belirir
- Tıklanınca modal (Bootstrap, jQuery UI veya başka yöntemle) açılır
- Modal içinde PDF dosyası PDF.js kullanarak görüntülenir

Sayfa responsive olmalı ve basit CSS ile tasarlanmalı.

---

## 📂 3. Media Upload & PDF Viewer Prompt

Yüklenen PDF dosyaları Magento'nun `pub/media/lookbook` dizinine kaydedilsin.

Kapak görseli yüklenirse `pub/media/lookbook/covers` dizininde tutulmalı.

Frontend'de modal açıldığında PDF.js viewer iframe içinde çağrılsın. Örnek iframe kullanımı:

```html
<iframe src="/pdfjs/web/viewer.html?file=/media/lookbook/myfile.pdf" width="100%" height="600"></iframe>
```

---

## 🔄 4. Modal Açılınca PDF Gösterimi

Frontend sayfasında her PDF için modal pencere oluşturan bir JavaScript/jQuery scripti ekle.

- Modal ilk açıldığında PDF.js iframe yüklenmeli.
- Modal kapanınca iframe resetlensin.

---

## 🥐 5. Ek Özellikler & Detaylar

Magento 2 modülüne şu ek özellikleri ekle:

- PDF yayınına göre sıralama (sıra no alanı eklensin)
- "Yalnızca belirli store view’lerde göster" seçeneği
- Sayfa başına 6 öğe gösterecek şekilde pagination
- Modül etkinleştirme/deaktif etme
- Setup ve uninstall scriptleri

---

## 🔟 6. Hepsini Bağlayan Final Prompt

Magento 2 için tam entegre çalışan bir modül üret:

- Backend admin arayüzü (form + grid)
- PDF & görsel upload
- Frontend görünüm (responsive grid + modal + PDF.js viewer)
- Route: /lookbook
- Gereken tüm XML layout, di.xml, routes.xml, block, phtml ve UI form bileşenleri oluşturulsun.
- PDF verileri repository aracılığıyla yüklensin.
- Modül adı: `Pdf_Lookbook`

