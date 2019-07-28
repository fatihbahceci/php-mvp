# php-mvp
PHP - MVP - Passive View



## rewrite config for ngnix (same as wordpress)

```nginx
location / {
			try_files $uri $uri/ /index.php?$args;
			
			if (!-e $request_filename)
			{
				rewrite ^(.+)$ /index.php?q=$1 last;
			}

			location ~* ^.+\.(jpeg|jpg|png|gif|bmp|ico|svg|css|js)$ {
				expires     max;
			}

			location ~ [^/]\.php(/|$) {
				fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
				if (!-f $document_root$fastcgi_script_name) {
					return  404;
				}
				#Your ip and port
				fastcgi_pass    127.0.0.1:5360;
				fastcgi_index   index.php;
				include         fastcgi_params;
			}
		}
```

