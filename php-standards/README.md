# PHP Sniffer and Mess detector

We will be following PSR 12 standards.

If you would like more information please review https://www.php-fig.org/psr/psr-12/


### 1) Download files that are neccessary for PHP MD and CS to work

* ##### Mess Detector
```https://phpmd.org/download/index.html```

* ##### Code Sniffer
```https://github.com/squizlabs/PHP_CodeSniffer```


---


### 2) How to use it ? 

The above two links already explain how to use it however you can use the following snippets too.


* ##### Code Sniffer

```
DIR_TO_YOUR_CODE_SNIFFER/bin/phpcs --standard=DIR_TO_YOUR_STANDARDS/phpcs.xml THE_FILES_DIRECTORY_YOU_HAVE_CHANGED 
```

* eg for a specific file
```
~/PHP_CodeSniffer/bin/phpcs --standard=~/standards/phpcs.xml ~/phpDocker/packt-admin/app/Http/Controllers/Controller.php
```

* eg for a folder

```
~/PHP_CodeSniffer/bin/phpcs --standard=~/standards/phpcs.xml ~/phpDocker/packt-admin/app/Http/Controllers
```


---

* ##### Mess Detector

```
DIR_TO_YOUR_MESS_DETECTOR/src/bin/phpmd THE_FILES_DIRECTORY_YOU_HAVE_CHANGED text DIR_TO_YOUR_STANDARDS/phpmd.xml
```


* eg for a specific file
```
PHP_MessDetector/src/bin/phpmd ~/phpDocker/packt-admin/app/Http/Controllers/Controller.php text ~/standards/phpmd.xml
```
* eg for a specific folder
```
for a folder
PHP_MessDetector/src/bin/phpmd ~/phpDocker/packt-admin/app/Http/Controllers text ~/standards/phpmd.xml
```
