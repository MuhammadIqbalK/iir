#!/bin/bash

# Production Deployment Script for IIR System
# This script helps build and deploy the production environment

set -e

echo "🚀 Starting IIR Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if .env file exists for production
if [ ! -f .env ]; then
    print_warning "No .env file found. Creating from .env.production template..."
    cp .env.production .env
    print_warning "Please update the .env file with your production settings before continuing!"
    print_warning "Especially update: APP_KEY, DB_HOST, DB_USERNAME, DB_PASSWORD, APP_URL"
    read -p "Press Enter to continue after updating .env file..."
fi

# Check if required directories exist
print_status "Creating required directories..."
mkdir -p public_html
mkdir -p docker/nginx/sites
mkdir -p docker/nginx/ssl

# Build Node.js assets
print_status "Building Vue.js frontend assets..."
pnpm install
pnpm run build

# Copy built assets to public_html
print_status "Setting up public directory structure..."
cp -r public/* public_html/ 2>/dev/null || true
mkdir -p public_html/build
cp -r dist/* public_html/build/ 2>/dev/null || true

# Build Docker containers
print_status "Building Docker containers for production..."
docker compose -f docker-compose.prod.yml build

# Optional: Run build service to ensure assets are properly built
print_status "Running build service..."
docker compose -f docker-compose.prod.yml --profile build up --build node

# Start the production services
print_status "Starting production services..."
docker compose -f docker-compose.prod.yml up -d

# Show status
print_status "Checking service status..."
docker compose -f docker-compose.prod.yml ps

# Generate APP_KEY if not set
if grep -q "YOUR_APP_KEY_HERE" .env; then
    print_warning "Generating new APP_KEY..."
    docker compose -f docker-compose.prod.yml exec app php /var/www/artisan key:generate --force
    print_warning "Please copy the generated APP_KEY to your .env file for persistence."
fi

# Run Laravel optimizations
print_status "Running Laravel optimizations..."
docker compose -f docker-compose.prod.yml exec app php /var/www/artisan config:cache
docker compose -f docker-compose.prod.yml exec app php /var/www/artisan route:cache
docker compose -f docker-compose.prod.yml exec app php /var/www/artisan view:cache

# Set proper permissions
print_status "Setting proper permissions..."
docker compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker compose -f docker-compose.prod.yml exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Next Steps:"
echo "1. Update your .env file with correct database credentials"
echo "2. Run database migrations: docker compose -f docker-compose.prod.yml exec app php artisan migrate"
echo "3. Seed your database if needed: docker compose -f docker-compose.prod.yml exec app php artisan db:seed"
echo "4. Configure your domain's DNS to point to this server"
echo "5. Set up SSL certificates (uncomment HTTPS configuration in docker/nginx/sites/default.conf)"
echo ""
echo "🌐 Your application should be available at: http://localhost"
echo "🔍 Check logs with: docker compose -f docker-compose.prod.yml logs -f"
echo "🛑 Stop services with: docker compose -f docker-compose.prod.yml down"
