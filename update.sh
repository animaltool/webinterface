composer self-update
composer update --dev
./flow flow:cache:flush --force
./flow doctrine:update
./flow cache:warmup