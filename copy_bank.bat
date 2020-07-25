@echo off

title Copy_file
set old_dir="\\server\bank\*.*"
set new_dir="D:\OpenServer\domains\artline.city\pages\import\"
move /Y %old_dir% %new_dir% 



