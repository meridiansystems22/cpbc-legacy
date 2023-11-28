FROM php:8.2-apache
# CPBC Enquiry System.
COPY . /var/www/html/
RUN mkdir -p uploads && chmod 777 uploads
EXPOSE 80
