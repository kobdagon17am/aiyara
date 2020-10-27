<?php
//echo shell_exec('git push "https://687b012772fc2b42ae5803b73b81c54e24d9ab13:x-oauth-basic@github.com/CuEiHzO/honda.git" master');
//echo '<hr>';
//echo shell_exec('git add -A');
//echo '<hr>';
//echo shell_exec('git commit -am "sv changes '.date('d-m-Y H:i:s').'"');
//echo '<hr>';
//echo shell_exec('git push "https://687b012772fc2b42ae5803b73b81c54e24d9ab13:x-oauth-basic@github.com/CuEiHzO/honda.git" master');
//echo '<hr>';
// echo shell_exec('git pull "https://687b012772fc2b42ae5803b73b81c54e24d9ab13:x-oauth-basic@github.com/CuEiHzO/honda.git" master');
// echo '<hr>';
echo shell_exec('cd local && php artisan cache:clear');
echo shell_exec('cd local && php artisan route:clear');
echo shell_exec('cd local && php artisan config:clear');
echo shell_exec('cd local && php artisan view:clear');


//git remote set-url origin https://github.com/CuEiHzO/honda.git
?>