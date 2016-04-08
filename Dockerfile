FROM php:5.6-alpine
RUN mkdir /storage
COPY perftest.php /
RUN chmod +x /perftest.php
VOLUME [/storage]
ENTRYPOINT [ "/perftest.php", "/storage"]
CMD ["10000", "1"]
