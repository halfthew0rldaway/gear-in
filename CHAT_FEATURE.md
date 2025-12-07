# Fitur Chat Customer-Admin

## Cara Kerja

Fitur chat ini menggunakan **database-based messaging system** dengan **auto-refresh (polling)** untuk memberikan pengalaman yang mirip real-time tanpa memerlukan WebSocket atau service eksternal.

### Arsitektur

1. **Database Structure:**
   - `conversations` table: Menyimpan percakapan antara customer dan admin
   - `messages` table: Menyimpan pesan-pesan dalam setiap percakapan

2. **Auto-Refresh Mechanism:**
   - JavaScript polling setiap 3 detik untuk mengambil pesan baru
   - Refresh otomatis setelah mengirim pesan
   - Berhenti saat tab tidak aktif (menghemat resource)

3. **Features:**
   - Customer dapat membuat percakapan baru atau melanjutkan percakapan yang ada
   - Admin dapat melihat semua percakapan dan membalas
   - Status percakapan: Open, Pending, Closed
   - Read status untuk pesan
   - Badge unread count di navigation

### Flow

**Customer Side:**
1. Customer klik "Chat" di navigation
2. Customer bisa membuat percakapan baru dengan subjek dan pesan
3. Atau melanjutkan percakapan yang sudah ada
4. Pesan auto-refresh setiap 3 detik untuk melihat balasan admin

**Admin Side:**
1. Admin klik "Chat" di navigation (dengan badge unread count)
2. Admin melihat daftar semua percakapan
3. Admin klik percakapan untuk membuka dan membalas
4. Admin bisa mengubah status percakapan (Open/Pending/Closed)
5. Pesan auto-refresh setiap 3 detik untuk melihat pesan baru dari customer

### Kelebihan Pendekatan Ini

✅ **Simple**: Tidak perlu setup WebSocket/Pusher  
✅ **Reliable**: Data tersimpan di database  
✅ **College Project Friendly**: Mudah dijelaskan dan dipahami  
✅ **Functional**: Tetap memberikan pengalaman chat yang baik  
✅ **Scalable**: Bisa di-upgrade ke real-time nanti jika diperlukan  

### Upgrade ke Real-Time (Opsional)

Jika ingin upgrade ke real-time di masa depan:
1. Install Laravel Echo + Pusher/Soketi
2. Setup broadcasting
3. Ganti polling dengan WebSocket listener
4. Database structure tetap sama, hanya mekanisme update yang berubah

### Routes

**Customer:**
- `GET /chat` - Daftar percakapan
- `GET /chat/{conversation}` - Detail percakapan
- `POST /chat` - Buat percakapan baru
- `POST /chat/{conversation}/message` - Kirim pesan

**Admin:**
- `GET /admin/chat` - Daftar semua percakapan
- `GET /admin/chat/{conversation}` - Detail percakapan
- `POST /admin/chat/{conversation}/message` - Kirim balasan
- `PATCH /admin/chat/{conversation}/status` - Update status

