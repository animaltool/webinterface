call composer self-update
call composer update --dev
call flow flow:cache:flush --force
call flow doctrine:migrate
call flow flow:core:migrate
call flow cache:warmup 

pause