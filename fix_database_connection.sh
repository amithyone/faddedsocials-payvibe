#!/bin/bash

# Fix database connection issue
# Run this on the server

cd /home/wolrdhome/public_html/core

echo "=== Checking .env File ==="

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "✗ .env file is missing!"
    echo "Checking for .env.example..."
    if [ -f ".env.example" ]; then
        echo "Copying .env.example to .env..."
        cp .env.example .env
        echo "⚠ You need to configure .env with your database credentials"
    else
        echo "✗ .env.example also not found"
        exit 1
    fi
fi

# Check database configuration
echo ""
echo "=== Current Database Configuration ==="
echo "DB_CONNECTION:"
grep "^DB_CONNECTION=" .env || echo "DB_CONNECTION not set"

echo ""
echo "DB_HOST:"
grep "^DB_HOST=" .env || echo "DB_HOST not set"

echo ""
echo "DB_PORT:"
grep "^DB_PORT=" .env || echo "DB_PORT not set"

echo ""
echo "DB_DATABASE:"
grep "^DB_DATABASE=" .env || echo "DB_DATABASE not set"

echo ""
echo "DB_USERNAME:"
grep "^DB_USERNAME=" .env || echo "DB_USERNAME not set"

echo ""
echo "DB_PASSWORD:"
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)
if [ -z "$DB_PASSWORD" ] || [ "$DB_PASSWORD" == "" ]; then
    echo "⚠ DB_PASSWORD is EMPTY or not set!"
    echo "This is likely the problem - database password is missing"
else
    echo "✓ DB_PASSWORD is set (hidden for security)"
fi

echo ""
echo "=== Recommendations ==="
echo "1. Check your .env file has correct database credentials"
echo "2. Make sure DB_PASSWORD is set (not empty)"
echo "3. Verify database user has proper permissions"
echo ""
echo "To edit .env file:"
echo "  nano .env"
echo "  or"
echo "  vi .env"
