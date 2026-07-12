#!/bin/bash
# ============================================================
#  start.sh — PPDB SMP Alamanah Setu
#  Deteksi deps → Install → Kill proses lama → Jalankan DB + server
# ============================================================

set -e
APP_DIR="$(cd "$(dirname "$0")" && pwd)"
HOST="0.0.0.0"
PORT="8000"
DB_SOCKET="/run/mysqld/mysqld.sock"
LOG_DB="/tmp/kantin_cashless_mariadb.log"
LOG_PHP="/tmp/kantin_cashless_php.log"
BROWSER_URL="http://localhost:${PORT}"
GREEN="\033[1;32m"
YELLOW="\033[1;33m"
RED="\033[1;31m"
CYAN="\033[1;36m"
NC="\033[0m"

step() { echo -e "\n${CYAN}▶ $1${NC}"; }
ok()   { echo -e "  ${GREEN}✔ $1${NC}"; }
warn() { echo -e "  ${YELLOW}⚠ $1${NC}"; }
fail() { echo -e "  ${RED}✘ $1${NC}"; }

echo -e "${CYAN}"
echo "  ╔══════════════════════════════════════════╗"
echo "  ║      Kantin Cashless K2HUB — Starter     ║"
echo "  ╚══════════════════════════════════════════╝"
echo -e "${NC}"

# ─── STEP 0: Deteksi & Install Tools yang Dibutuhkan ────────
step "0/7 · Mendeteksi tools & dependencies..."

MISSING=()

# Deteksi PHP
PHP_OK=0
if command -v php &>/dev/null; then
  PHP_MAJOR=$(php -r 'echo PHP_MAJOR_VERSION;')
  PHP_MINOR=$(php -r 'echo PHP_MINOR_VERSION;')
  PHP_VER="${PHP_MAJOR}.${PHP_MINOR}"
  PHP_NUM=$((PHP_MAJOR * 10 + PHP_MINOR))
  if [ "$PHP_NUM" -ge 82 ]; then
    ok "PHP $PHP_VER sudah terinstall"
    PHP_OK=1
  else
    warn "PHP $PHP_VER terlalu renda (butuh ≥ 8.2)"
  fi
else
  warn "PHP tidak ditemukan"
fi

# Deteksi MariaDB / MySQL
MARIADB_OK=0
if command -v mariadbd &>/dev/null; then
  ok "MariaDB (mariadbd) sudah terinstall"
  MARIADB_OK=1
elif command -v mysqld &>/dev/null; then
  ok "MySQL (mysqld) sudah terinstall"
  MARIADB_OK=1
else
  warn "MariaDB/MySQL tidak ditemukan"
fi

# Deteksi Composer
COMPOSER_OK=0
if command -v composer &>/dev/null; then
  ok "Composer sudah terinstall"
  COMPOSER_OK=1
else
  warn "Composer tidak ditemukan"
fi

# Deteksi Node.js
NODE_OK=0
if command -v node &>/dev/null; then
  NODE_VER=$(node -v | sed 's/v//')
  ok "Node.js v$NODE_VER sudah terinstall"
  NODE_OK=1
else
  warn "Node.js tidak ditemukan"
fi

# Deteksi NPM
NPM_OK=0
if command -v npm &>/dev/null; then
  ok "npm sudah terinstall"
  NPM_OK=1
else
  warn "npm tidak ditemukan"
fi

# ─── Install yang belum ada ─────────────────────────────────
install_php() {
  warn "Menginstall PHP 8.2 & ekstensi yang dibutuhkan..."
  if command -v apt-get &>/dev/null; then
    sudo apt-get update -qq
    sudo apt-get install -y -qq php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl \
      php8.2-zip php8.2-gd php8.2-mysql php8.2-sqlite3 php8.2-bcmath php8.2-intl php8.2-tokenizer 2>/dev/null \
    || sudo apt-get install -y -qq php php-cli php-mbstring php-xml php-curl \
      php-zip php-gd php-mysql php-sqlite3 php-bcmath php-intl php-tokenizer
  elif command -v apk &>/dev/null; then
    apk add --no-cache php82 php82-cli php82-mbstring php82-xml php82-curl \
      php82-zip php82-gd php82-mysql php82-sqlite3 php82-bcmath php82-intl
  elif command -v pacman &>/dev/null; then
    sudo pacman -S --noconfirm php php-gd php-intl php-sqlite
  else
    fail "Tidak bisa auto-install PHP — package manager tidak dikenali"
    exit 1
  fi
  ok "PHP berhasil diinstall"
}

install_mariadb() {
  warn "Menginstall MariaDB..."
  if command -v apt-get &>/dev/null; then
    sudo apt-get install -y -qq mariadb-server mariadb-client
  elif command -v apk &>/dev/null; then
    apk add --no-cache mariadb mariadb-client
  elif command -v pacman &>/dev/null; then
    sudo pacman -S --noconfirm mariadb
  else
    fail "Tidak bisa auto-install MariaDB — package manager tidak dikenali"
    exit 1
  fi
  ok "MariaDB berhasil diinstall"
}

install_composer() {
  warn "Menginstall Composer..."
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 2>/dev/null
  ok "Composer berhasil diinstall"
}

install_nodejs() {
  warn "Menginstall Node.js & npm..."
  if command -v apt-get &>/dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
    sudo apt-get install -y -qq nodejs
  elif command -v apk &>/dev/null; then
    apk add --no-cache nodejs npm
  elif command -v pacman &>/dev/null; then
    sudo pacman -S --noconfirm nodejs npm
  else
    fail "Tidak bisa auto-install Node.js — package manager tidak dikenali"
    exit 1
  fi
  ok "Node.js & npm berhasil diinstall"
}

if [ "$PHP_OK" -eq 0 ]; then
  install_php
  # Update var setelah install
  PHP_VER=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
  ok "PHP $PHP_VER terinstall"
fi

if [ "$MARIADB_OK" -eq 0 ]; then
  install_mariadb
fi

if [ "$COMPOSER_OK" -eq 0 ]; then
  install_composer
fi

if [ "$NODE_OK" -eq 0 ] || [ "$NPM_OK" -eq 0 ]; then
  install_nodejs
fi

ok "Semua tools sudah siap"

# ─── STEP 1: Install Project Dependencies ───────────────────
step "1/7 · Memeriksa dependencies project..."

cd "$APP_DIR"

# Composer dependencies
if [ ! -f "composer.lock" ] || [ ! -d "vendor" ]; then
  warn "vendor/ belum ada — menjalankan composer install..."
  composer install --no-interaction --prefer-dist
  ok "Composer dependencies terinstall"
else
  ok "Composer dependencies sudah ada"
fi

# Node dependencies
if [ ! -d "node_modules" ]; then
  warn "node_modules/ belum ada — menjalankan npm install..."
  npm install --quiet
  ok "NPM dependencies terinstall"
else
  ok "NPM dependencies sudah ada"
fi

# ─── STEP 2: Setup Environment ─────────────────────────────
step "2/7 · Menyiapkan environment..."

if [ ! -f ".env" ]; then
  warn ".env belum ada — menyalin dari .env.example"
  cp .env.example .env
  php artisan key:generate --quiet
  ok ".env berhasil dibuat & APP_KEY di-generate"
else
  ok ".env sudah ada"
  if grep -q "APP_KEY=$" .env 2>/dev/null; then
    php artisan key:generate --quiet
    ok "APP_KEY di-generate"
  fi
fi

# Mendapatkan nama database dari .env
DB_DATABASE=$(grep '^DB_DATABASE=' .env | cut -d '=' -f2 | tr -d '\r')
if [ -z "$DB_DATABASE" ]; then
  DB_DATABASE="laravel_kantincashless"
fi

# ─── STEP 3: Build Frontend Assets ─────────────────────────
step "3/7 · Build frontend assets..."

if [ ! -f "public/build/manifest.json" ] && [ ! -f "public/build/.vite/manifest.json" ]; then
  warn "Build belum ada — menjalankan npm run build..."
  npm run build
  ok "Frontend assets berhasil di-build"
else
  ok "Frontend assets sudah ada"
fi

# ─── STEP 4: Kill semua proses lama ────────────────────────
step "4/7 · Membersihkan proses lama..."

pkill -f "mariadbd"        2>/dev/null && warn "mariadbd dihentikan"    || true
pkill -f "mysqld"          2>/dev/null && warn "mysqld dihentikan"      || true
pkill -f "artisan serve"   2>/dev/null && warn "artisan serve dihentikan" || true
pkill -f "php -S"          2>/dev/null && warn "php -S dihentikan"      || true

rm -f "$DB_SOCKET" "${DB_SOCKET}.lock" /var/lib/mysql/*.pid /tmp/mysql*.pid 2>/dev/null || true
sleep 1
ok "Proses lama sudah dibersihkan"

# ─── STEP 5: Jalankan MariaDB ───────────────────────────────
step "5/7 · Menjalankan MariaDB..."

# Pastikan datadir ada
if [ ! -d "/var/lib/mysql/mysql" ]; then
  warn "Inisialisasi data directory MariaDB..."
  if command -v mysql_install_db &>/dev/null; then
    sudo mysql_install_db --user="$(whoami)" --datadir=/var/lib/mysql 2>/dev/null
  elif command -v mariadb-install-db &>/dev/null; then
    sudo mariadb-install-db --user="$(whoami)" --datadir=/var/lib/mysql 2>/dev/null
  fi
fi

rm -f "$LOG_DB"
mariadbd \
  --user="$(whoami)" \
  --datadir=/var/lib/mysql \
  --socket="$DB_SOCKET" \
  --log-error="$LOG_DB" \
  --skip-grant-tables \
  2>&1 &
DB_PID=$!

READY=0
for i in $(seq 1 30); do
  sleep 1
  if mysql -u root --socket="$DB_SOCKET" -e "SELECT 1" >/dev/null 2>&1; then
    ok "MariaDB siap (${i}s) — PID: ${DB_PID}"
    READY=1
    break
  fi
  printf "  menunggu... %ds\r" "$i"
done

if [ "$READY" -eq 0 ]; then
  fail "MariaDB gagal start. Lihat log: $LOG_DB"
  tail -20 "$LOG_DB"
  exit 1
fi

mysql -u root --socket="$DB_SOCKET" \
  -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
  >/dev/null 2>&1
ok "Database ${DB_DATABASE} tersedia"

# ─── STEP 6: Jalankan Migration ────────────────────────────
step "6/7 · Menjalankan migrasi database..."

php artisan config:clear --quiet 2>/dev/null || true
php artisan view:clear --quiet 2>/dev/null || true
MIGRATE_OUT=$(php artisan migrate --force 2>&1)
echo "$MIGRATE_OUT" | sed 's/^/  /'
ok "Migrasi selesai"

# ─── STEP 7: Jalankan PHP Server ───────────────────────────
step "7/7 · Menjalankan PHP server..."

rm -f "$LOG_PHP"
php artisan serve --host="$HOST" --port="$PORT" > "$LOG_PHP" 2>&1 &
PHP_PID=$!

READY=0
for i in $(seq 1 15); do
  sleep 1
  if grep -q "Server running" "$LOG_PHP" 2>/dev/null; then
    ok "PHP server siap (${i}s) — PID: ${PHP_PID}"
    READY=1
    break
  fi
  printf "  menunggu... %ds\r" "$i"
done

if [ "$READY" -eq 0 ]; then
  fail "PHP server gagal start. Lihat log: $LOG_PHP"
  cat "$LOG_PHP"
  exit 1
fi

# ─── Buka Browser ──────────────────────────────────────────
step "Membuka browser eksternal..."

sleep 1
termux-open-url "$BROWSER_URL" 2>/dev/null \
  && ok "Browser dibuka: ${BROWSER_URL}" \
  || warn "termux-open-url tidak tersedia — buka manual: ${BROWSER_URL}"

# ─── Summary ───────────────────────────────────────────────
echo -e "\n${GREEN}  ══════════════════════════════════════════${NC}"
echo -e "${GREEN}  ✔  Aplikasi Kantin Cashless K2HUB AKTIF    ${NC}"
echo -e "${GREEN}  ══════════════════════════════════════════${NC}"
echo -e "  URL     : ${CYAN}${BROWSER_URL}${NC}"
echo -e "  MariaDB : PID ${DB_PID}  |  Log: ${LOG_DB}"
echo -e "  PHP     : PID ${PHP_PID}  |  Log: ${LOG_PHP}"
echo -e "\n  Tekan ${RED}Ctrl+C${NC} untuk menghentikan semua proses\n"

# ─── Trap Ctrl+C → Kill semua ──────────────────────────────
cleanup() {
  echo -e "\n${YELLOW}▶ Menghentikan semua proses...${NC}"
  kill "$PHP_PID" "$DB_PID" 2>/dev/null || true
  pkill -f "mariadbd" 2>/dev/null || true
  rm -f "$DB_SOCKET" 2>/dev/null || true
  echo -e "${GREEN}✔ Semua proses dihentikan.${NC}\n"
  exit 0
}
trap cleanup SIGINT SIGTERM

# ─── Tail log PHP server (live output) ─────────────────────
tail -f "$LOG_PHP" &
wait "$PHP_PID"
