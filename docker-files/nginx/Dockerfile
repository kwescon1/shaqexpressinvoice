# Use the stable version of Nginx based on the Alpine Linux distribution
FROM nginx:stable-alpine

# Create the directory where your application files will reside
RUN mkdir -p /var/www/shaqexpressinvoice

# Set the working directory inside the container to /var/www/shaqexpressinvoice
WORKDIR /var/www/shaqexpressinvoice

# Add the Nginx configuration file from your local machine to the container
# This file should define how Nginx serves your application
ADD docker-files/nginx/conf.d/app.conf /etc/nginx/conf.d/app.conf

# Set environment variables for the custom Nginx user and group
# These variables can be used to customize the Nginx process ownership
ENV NGINXUSER=shaqexpressinvoice
ENV NGINXGROUP=shaqexpressinvoice

# Modify the main Nginx configuration file to replace the default user (www-data)
# with your custom user (shaqexpressinvoice)
# This ensures Nginx runs under the 'shaqexpressinvoice' user
RUN sed -i "s/user www-data;/user ${NGINXUSER};/g" /etc/nginx/nginx.conf

# Add a new user 'shaqexpressinvoice' with the specified group, shell, and home directory
# The -D flag avoids creating a home directory, which is typical for service users
RUN adduser -g ${NGINXGROUP} -s /bin/sh -D ${NGINXUSER}

# Set the ownership of the /var/www/shaqexpressinvoice directory to the custom Nginx user and group
# This ensures that the Nginx process can read and serve the files in this directory
RUN chown -R ${NGINXUSER}:${NGINXGROUP} /var/www/shaqexpressinvoice
