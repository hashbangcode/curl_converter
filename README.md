# CURL Command Converter

Convert a curl HTTP request from one format to another. This allows
curl commands of various types to be parsed and output as equivalent
code. 

## Currently supported inputs:
- Curl

## Currently supported outputs:
- PHP Code

## Install

    composer require hashbangcode/curl_converter

## Usage

Require your composer autoload file and then.

```
    <?php
   
    use Hashbangcode\CurlConverter\Input\CurlInput;
    use Hashbangcode\CurlConverter\Output\PhpOutput;
    use Hashbangcode\CurlConverter\CurlConverter;
    
    $input = new CurlInput();
    $output = new PhpOutput();
    $converter = new CurlConverter($input, $output);
    
    $command = 'curl https://www.hashbangcode.com/';
    
    $converted = $converter->convert($command);
```
    
The following output is produced from the above code (contained in 
the $converted variable).

```
    <?php
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, "https://www.example.com/");
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl_handle);
    if (curl_errno($curl_handle)) {
      echo 'Error:' . curl_error($curl_handle);
    }
    curl_close($curl_handle);
```

The $result variable will contain the result of the curl request.

## Technical Details

In order to allow formats to be swapped from one form to another
an underlying object called CurlParameters is used. This class can 
be used along with an output class to generate the same result
without parsing the curl command input.

```
    <?php
    
    use Hashbangcode\CurlConverter\Output\PhpOutput;
    use Hashbangcode\CurlConverter\CurlParameters;
    
    $output = new PhpOutput();
    $curlConverter = new CurlConverter();
    $curlConverter->setUrl('https://www.example.com/');
    $result = $output->convert($curlConverter);
```

The $result parameter now contains the PHP code for the curl request.