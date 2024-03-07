# Install
```
composer require hera/captcha
```

# Check captcha is ok
```
Hash::check($inputs['captcha'] , $requests->input('key'))
```