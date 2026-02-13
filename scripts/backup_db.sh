#!/bin/bash

# Configurações
USER="root"
PASSWORD="sua_senha_mysql"
DB_NAME="sistema_mineracao"
BACKUP_DIR="/var/backups/mineradora"
DATE=$(date +%Y-%m-%d_%H-%M-%S)
FILE_NAME="backup_$DB_NAME_$DATE.sql.gz"

# Cria pasta se não existir
mkdir -p $BACKUP_DIR

# Executa o dump e comprime
mysqldump -u $USER -p$PASSWORD $DB_NAME | gzip > $BACKUP_DIR/$FILE_NAME

# Remove backups com mais de 30 dias
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -delete

echo "Backup realizado: $FILE_NAME"