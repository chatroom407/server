if ! nc -z localhost 8081; then
    echo "Usługa na porcie 8081 jest niedostępna. Otwieram serverHttp.php..."
    php serverHttp.php
else
    echo "Usługa na porcie 8081 działa poprawnie."
fi