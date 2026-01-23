#!/bin/bash

# Interactive SSH connection script
# This will handle the "yes" prompt automatically and give you a working terminal

SERVER="104.207.95.218"
PORT="22"
USERNAME="root"
PASSWORD="Pr7CdWcpaBNY84l152"

echo "Connecting to $SERVER..."
echo "This will automatically accept the host key and connect you to an interactive session."
echo ""

# Create expect script inline to handle connection
/usr/bin/expect << 'EOF'
set timeout 10
spawn ssh -o StrictHostKeyChecking=no -p 22 root@104.207.95.218
expect {
    "password:" {
        send "Pr7CdWcpaBNY84l152\r"
    }
    "Password:" {
        send "Pr7CdWcpaBNY84l152\r"
    }
    "(yes/no)?" {
        send "yes\r"
        expect "password:"
        send "Pr7CdWcpaBNY84l152\r"
    }
    timeout {
        puts "Connection timeout"
        exit 1
    }
}
interact
EOF
