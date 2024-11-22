FROM minio/minio

# Copy the start script from the correct path
COPY ./docker-files/minio/config/start-minio.sh /usr/local/bin/start-minio.sh


# Set the entrypoint to the script
ENTRYPOINT ["/usr/local/bin/start-minio.sh"]
