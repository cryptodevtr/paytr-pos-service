# PayTR POS Seçim Servisi

PayTR Case Study - E-ticaret ödeme altyapısı için en düşük maliyetli POS'u seçen mikroservis.

## 📋 İçindekiler

- [Proje Hakkında](#proje-hakkında)
- [Teknolojiler](#teknolojiler)
- [Gereksinimler](#gereksinimler)
- [Kurulum](#kurulum)
- [Docker ile Çalıştırma](#docker-ile-çalıştırma)
- [Manuel Çalıştırma](#manuel-çalıştırma)
- [API Dokümantasyonu](#api-dokümantasyonu)
- [Testler](#testler)
- [Proje Yapısı](#proje-yapısı)
- [Mimari](#mimari)
- [Troubleshooting](#troubleshooting)
- [Lisans](#lisans)

## 🎯 Proje Hakkında

Bu servis, ödeme anında müşterinin kart bilgileri üzerinden en düşük maliyetli POS'u seçerek işlemi yönlendirmeyi hedefler. Sistem farklı POS sağlayıcıları ile entegre çalışır ve her POS sağlayıcısının taksit, kart tipi, kart markası, kur, komisyon oranı gibi parametrelerini dikkate alarak optimizasyon yapar.

### Özellikler

- ✅ **POS Oran Bilgilerini API'den Alma** - Mock API üzerinden güncel oranları çeker
- ✅ **POS Seçim Algoritması** - En düşük maliyetli POS'u dinamik olarak seçer
- ✅ **Otomatik Güncelleme** - Cron job ile periyodik veri güncellemesi
- ✅ **Manuel Tetikleme** - HTTP endpoint ile manuel senkronizasyon
- ✅ **Docker Support** - Kolay dağıtım için tam Docker desteği
- ✅ **SOLID Principles** - Temiz ve bakımı kolay kod yapısı
- ✅ **Exception Handling** - Merkezi hata yönetimi

## 🛠 Teknolojiler

| Teknoloji | Versiyon | Açıklama |
|-----------|----------|----------|
| Laravel | 12.x | PHP Framework |
| PHP | 8.2+ | Programlama Dili |
| MySQL | 8.0 | Veritabanı |
| Redis | 7.x | Cache & Queue |
| Docker | 24.x | Containerization |
| Nginx | Alpine | Web Server |
| PHPUnit | 10.x | Testing |

## 📦 Gereksinimler

- Docker & Docker Compose (Önerilen)
- veya PHP 8.2+, Composer, MySQL, Redis

## 🚀 Kurulum

### Docker ile Kurulum (Önerilen)

```bash
# 1. Projeyi klonlayın
git clone https://github.com/yourusername/paytr-pos-service.git
cd paytr-pos-service

# 2. Environment dosyasını oluşturun
cp .env.example .env

# 3. Docker imajlarını build edin
docker-compose build

# 4. Container'ları başlatın
docker-compose up -d

# 5. Migration'ları çalıştırın
docker-compose exec app php artisan migrate

# 6. İlk veri senkronizasyonunu yapın
docker-compose exec app php artisan tinker --execute="App\Jobs\SyncPosRatesJob::dispatchSync();"

# 7. Servisin çalıştığını kontrol edin
curl http://localhost:8080/api/pos/select \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"amount":1000,"installment":6,"currency":"TRY","card_type":"credit"}'


#Docker

# Tüm servisleri başlat
docker-compose up -d

# Sadece belirli servisleri başlat
docker-compose up -d app mysql redis

# Build'i zorla
docker-compose up -d --build

#Docker Container

# App container'ına bağlan
docker-compose exec app bash

# MySQL'e bağlan
docker-compose exec mysql mysql -u paytr_user -p

# Redis'e bağlan
docker-compose exec redis redis-cli

# Tüm servisleri durdur
docker-compose down

# Volume'leri de temizle (veriler silinir!)
docker-compose down -v

# Servisleri yeniden başlat
docker-compose restart
