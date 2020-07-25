SET SOURCEDIR=D:\#WORK\OSPanel\userdata\MySQL-5.6\

set hour=%TIME:~0,2%
set minute=%TIME:~3,2%
set second=%TIME:~6,2%
set HHMMSS=%hour%-%minute%

for /d %%i in (%SOURCEDIR%\*) do "D:\#WORK\OSPanel\modules\database\MySQL-5.6\bin\mysqldump.exe" -uroot -hlocalhost -c -n %%~ni | "c:\Program Files\7-Zip\7z.exe" a -tgzip -si"%%~ni_%DATE%_%HHMMSS%.sql" "D:\backups\%DATE%_%HHMMSS%\%%~ni.sql.gzip"

eachfile.exe -purge -r -w -e -d 13 -l 0 -dir D:\backups

exit