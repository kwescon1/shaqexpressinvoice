#!/bin/bash

# Enable debugging: prints each command before executing it, useful for troubleshooting
set -x

# Start MinIO in the background
minio server /data --console-address ":9001"

# Wait for MinIO to start. Adjust the sleep time if necessary
sleep 10

# Configure the MinIO client (mc) with the MinIO server
mc alias set myminio http://minio:9000 ${MINIO_ROOT_USER} ${MINIO_ROOT_PASSWORD}

# Create the bucket if it does not exist
mc mb myminio/${MINIO_BUCKET_NAME} --ignore-existing

# Set the bucket to allow public access
mc policy set public myminio/${MINIO_BUCKET_NAME}

# Optionally set anonymous public access
# This will allow unauthenticated users to access the bucket (use with caution)
mc anonymous set public myminio/${MINIO_BUCKET_NAME}

# Keep the container running after setup is complete
wait
