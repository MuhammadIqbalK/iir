# IIR System - Production Deployment Guide

This guide provides comprehensive instructions for deploying the IIR (Laravel + Vue.js) application to production using Docker.

## 🏗️ Architecture Overview

The production deployment consists of:

- **Laravel Application** (PHP 8.3-FPM) - Backend API and business logic
- **Nginx** (Alpine) - Web server and reverse proxy
- **Redis** (Alpine) - Caching and session storage
- **PostgreSQL** (External) - Production database (recommended external, not containerized)
- **Node.js** (Alpine) - Build service for Vue.js frontend assets

## 📋 Prerequisites

### System Requirements
- Docker Engine 20.10+
- Docker Compose V2+
- pnpm package manager
- External PostgreSQL database (recommended)

### Environment Setup
Ensure you have the following configured:
- PostgreSQL database with appropriate credentials
- Domain name pointing to your server
- SSL certificates (for HTTPS)

## 🚀 Quick Deployment

### 1. Prepare Environment

```bash
# Clone or navigate to your project directory
cd /path/to/iir

# Make deployment script executable
chmod +x deploy.sh

# Run the deployment script
./deploy.sh
```

The script will:
- Create necessary directories
- Build Vue.js frontend assets
- Build and start Docker containers
- Optimize Laravel for production
- Set proper permissions

### 2. Configure Environment

Update your `.env` file with production settings:

```bash
# Application
APP_NAME="IIR System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (External PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=iir_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Security
APP_FORCE_HTTPS=true
```

### 3. Run Database Migrations

```bash
docker compose -f docker-compose.prod.yml exec app php /var/www/artisan migrate --force
```

### 4. Seed Database (Optional)

```bash
docker compose -f docker-compose.prod.yml exec app php /var/www/artisan db:seed --force
```

## 📁 Directory Structure

The deployment follows the secure structure recommended in the Vuexy documentation:

```
iir/
├── laravel/                    # Laravel application (secure, not web-accessible)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── artisan
│   └── composer.json
├── public_html/                # Web-accessible directory
│   ├── build/                 # Vue.js built assets
│   ├── index.php              # Laravel entry point
│   └── public files...
├── docker-compose.prod.yml      # Production Docker configuration
├── Dockerfile.prod            # Production Docker image
└── docker/
    └── nginx/
        ├── nginx.conf          # Nginx main configuration
        └── sites/
            └── default.conf     # Site-specific configuration
```

## 🔧 Manual Deployment Steps

If you prefer manual deployment instead of using the script:

### 1. Build Frontend Assets

```bash
# Install dependencies
pnpm install

# Build for production
pnpm run build

# Copy to public directory
mkdir -p public_html/build
cp -r public/* public_html/
cp -r dist/* public_html/build/ 2>/dev/null || echo "No dist folder found"
```

### 2. Build Docker Images

```bash
docker compose -f docker-compose.prod.yml build
```

### 3. Start Services

```bash
docker compose -f docker-compose.prod.yml up -d
```

### 4. Laravel Optimizations

```bash
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### 5. Set Permissions

```bash
docker compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker compose -f docker-compose.prod.yml exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

## 🌐 SSL Configuration

### For HTTPS (Recommended)

1. Place SSL certificates in `docker/nginx/ssl/`:
   - `cert.pem` - SSL certificate
   - `key.pem` - Private key

2. Uncomment HTTPS configuration in `docker/nginx/sites/default.conf`

3. Update your `.env` file:
   ```bash
   APP_URL=https://your-domain.com
   APP_FORCE_HTTPS=true
   ```

### Using Let's Encrypt

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificates
sudo certbot certonly --standalone -d your-domain.com

# Copy to docker directory
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem docker/nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem docker/nginx/ssl/key.pem
```

## 📊 Monitoring and Maintenance

### Check Service Status

```bash
docker compose -f docker-compose.prod.yml ps
```

### View Logs

```bash
# All services
docker compose -f docker-compose.prod.yml logs -f

# Specific service
docker compose -f docker-compose.prod.yml logs -f app
docker compose -f docker-compose.prod.yml logs -f nginx
```

### Health Check

Access `http://your-domain.com/health` to verify services are running.

## 🔒 Security Considerations

### Production Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ External PostgreSQL database
- ✅ Secure directory structure (laravel/ separate from public_html/)
- ✅ Nginx blocks access to sensitive files
- ✅ Security headers configured
- ✅ SSL/TLS enabled
- ✅ Strong database passwords
- ✅ Regular security updates

### Recommended Headers

The Nginx configuration includes:
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- X-Content-Type-Options: nosniff
- Content-Security-Policy: default-src 'self'

## 🚨 Troubleshooting

### Common Issues

#### 1. Database Connection Failed
```bash
# Check database credentials
docker compose -f docker-compose.prod.yml exec app php artisan tinker
>>> DB::connection()->getPdo();
```

#### 2. Permissions Errors
```bash
# Reset storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 3. Assets Not Loading
```bash
# Rebuild frontend assets
docker compose -f docker-compose.prod.yml --profile build up --build node

# Check Nginx configuration
docker compose -f docker-compose.prod.yml exec nginx nginx -t
```

#### 4. 502 Bad Gateway
```bash
# Check PHP-FPM status
docker compose -f docker-compose.prod.yml logs app

# Restart services
docker compose -f docker-compose.prod.yml restart app nginx
```

## 🔄 Backup Strategy

### Database Backups

```bash
# Create backup
docker compose -f docker-compose.prod.yml exec app php artisan backup:run --only-db

# Schedule daily backups (add to crontab)
0 2 * * * cd /path/to/iir && docker compose -f docker-compose.prod.yml exec app php artisan backup:run --only-db
```

### Application Backups

```bash
# Backup entire application
tar -czf backup-$(date +%Y%m%d).tar.gz laravel/ public_html/ docker/
```

## 📈 Performance Optimization

### Redis Caching

The application uses Redis for:
- Session storage
- Application cache
- Queue processing

Monitor Redis usage:
```bash
docker compose -f docker-compose.prod.yml exec redis redis-cli info
```

### PHP OPcache

Enabled by default with optimized settings:
- Memory: 128MB
- Max accelerated files: 4000

## 🛠️ Development vs Production

| Feature | Development | Production |
|----------|-------------|-------------|
| Debug Mode | `APP_DEBUG=true` | `APP_DEBUG=false` |
| Database | Local/SQLite | External PostgreSQL |
| Web Server | Sail/Nginx | Nginx (optimized) |
| Assets | `npm run dev` | Pre-built with pnpm |
| Cache | File | Redis |
| Directory Structure | Single | Secure (separated) |

## 📞 Support

For deployment issues:
1. Check logs: `docker compose -f docker-compose.prod.yml logs -f`
2. Verify configuration: `docker compose -f docker-compose.prod.yml config`
3. Health check: Access `/health` endpoint
4. Review this documentation

## 🔄 Updates and Maintenance

### Updating Application

```bash
# Pull latest code
git pull origin main

# Update dependencies
pnpm install
composer install

# Rebuild assets
pnpm run build

# Update containers
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml up -d

# Clear caches
docker compose -f docker-compose.prod.yml exec app php artisan config:clear
docker compose -f docker-compose.prod.yml exec app php artisan route:clear
docker compose -f docker-compose.prod.yml exec app php artisan view:clear

# Rebuild caches
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### Database Updates

```bash
# Run migrations
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# If needed, seed new data
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --class=NewSeeder
```

---

**🎉 Your IIR application is now ready for production deployment!**
