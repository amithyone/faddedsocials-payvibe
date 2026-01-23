#!/bin/bash

# Simple script to connect to server interactively
# This opens a normal SSH session where you can type commands

SERVER="104.207.95.218"
PORT="22"
USERNAME="root"
PASSWORD="Pr7CdWcpaBNY84l152"

echo "Connecting to server $SERVER..."
echo "You will be prompted for password: $PASSWORD"
echo ""
echo "Once connected, run these commands:"
echo "  cd /home/wolrdhome/public_html/core"
echo "  # Then check and fix config directory"
echo ""

# Use sshpass if available, otherwise regular SSH
if command -v sshpass &> /dev/null; then
    sshpass -p "$PASSWORD" ssh -o StrictHostKeyChecking=no -p $PORT $USERNAME@$SERVER
else
    # Regular SSH - user will need to type password
    ssh -o StrictHostKeyChecking=no -p $PORT $USERNAME@$SERVER
fi
