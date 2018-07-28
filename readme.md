# Leveraging Cognitive Computation Text Extraction Tools to Detect Emotions from Facial Expressions

This project is a multi-class classification system that can detect whether a given image contains a face expression.  If it does, it is classified according to six possible emotions: sadness, anger, surprise, happiness, disgust, fear. 
Microsoft Cognitive Services have been leveraged to extract tags from training and test set images and create a vectorial space using bag of word model (we employed term frequency) that has been fed to different classification algorithms. 
Our system has been validated on 35712 images annotated and extracted the Microsoft FER+ dataset.
Moreover the system extracts all the Facebook public images of the logged user and classify each of them in the six classes previously mentioned.


## Typical Scenario

The user can decide whether to classify the image by logging in with his/her user profile through the *facebook* entry  or by uploading the image through the *upload* button. In both cases, the architecture works in the same way.
Suppose that the user had decided to login on Facebook. The user is therefore redirected to *Facebook.com* to start  the authentication flow. After a successful log in, the user is redirected back to our server where he/she can see the last twenty five photos that he/she had uploaded on Facebook. After one or images have been chosen, a task in background starts to sent the image to the Computer Vision Services and Emotion Services to fetch the related metadata. 
The *description tags* returned from Computer Vision are sent to our classification to predict the corresponding emotion.
Once the computation has been completed, the user can see for that image the results of Emotion Services and the results of our classification.

## Requirements

You need to: 

- Apache Server with PHP 7 and Mysql
- Apache Spark Server 1.6.1 

They can be in the same machine or in a different machine with different IP. 

## Installation & Configuration


In this repository you can find all files in order to execute all project correctly. 

There are three main blocks: 

- **CodeIgniter Framework**. In order to install the Codeigniter on your server you just copy this file on your public_html folder: 

- - ./application/*
  - ./css/*
  - ./fonts/*
  - ./img/*
  - ./js/*
  - ./system
  - ./index.html
  - ./.htaccess

- **Apache Spark Rest Service**. In order to install this rest service just copy into your Apache Spark folder this file: 

- - ./python/NaiveBayesClassifier.py
  - ./python/service.py
  - ./.python/data/*
  - ./python/model/*

  You have to install all import dependencies on your server. 

- **Sql Dump (sql folder)**

To run correctly our  application, you have to modify the following files:

* application/config/cognitive_services.php
* application/config/database.php
* application/config/facebook.php


Configuring ./application/config/cognitive_services.php
--
You have to add your cognitive services api key in this variables: 
```php
$config['cs_moderation_name']		= " YOUR CONTENT MODERATOR API NAME";
$config['cs_moderation_key1']		= "";
$config['cs_moderation_key2']		= "";
$config['cs_moderation_endpoint'] 	= ""; // URL


// Vision 
$config['cs_vision_name']		= "YOUR VISION SERVICE API NAME";
$config['cs_vision_key1'] 		= "";
$config['cs_vision_key2'] 		= "";
$config['cs_vision_endpoint'] 	= ""; // URL

// Emotion 
$config['cs_emotion_name']		= "YOUR EMOTION SERVICE API NAME";
$config['cs_emotion_key1'] 		= "";
$config['cs_emotion_key2'] 		= "";
$config['cs_emotion_endpoint'] 	= ""; // URL
```
Configuring ./application/config/database.php
--

Change the parameter to connect with your mysql DB. 

You need also to create your database, by uploading  `./sql/empty_backup.sql` into your mysql server.



```php
if( ('http://'.$_SERVER['HTTP_HOST']) == 'your_url'){
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'localhost', // hostname
		'username' => '', // db username
		'password' => '', // db password
		'database' => '', // db name
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'production'),
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
}
```
Configuring ./application/config/facebook.php
--
You have to add yout facebook_app_id and your secret key. 

Don't change the permission and to make the application work with other you have to confirm that permission with Facebook Validation Tools. 

```php
$config['facebook_app_id']              = '';
$config['facebook_app_secret']          = '';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'home/login'; 
$config['facebook_logout_redirect_url'] = 'home';
$config['facebook_permissions']         = array('email', 'public_profile', 'user_photos', 'user_managed_groups');
$config['facebook_graph_version']       = 'v2.12';
$config['facebook_auth_on_load']        = true;
```

Configuring Photos Upload
-- 

In order to perform the image upload, you have to add 777 permits in the folder **./img/uploads/**

Configuring ./js/main.js
--
If you want to create your own Apache Spark Classifier you have to change at line 91 and 96 the url/ip of the service. 

Currently is setted to use our service in located at our University, but if you need to install your custom service (python folder) you have to change that line. 

Line 91:

```javascript
var classificationUrl = 'http://192.167.155.71/acorriga/classifier';
```
Line 96:

```javascript
headers: {  'Access-Control-Allow-Origin': 'http://192.167.155.71/acorriga/classifier' }
```




Configuring and Run Apache Spark Web Service
--

In order to run correctly the python files, you have to install in your machine Apache Spark. We don't provide any guidelines about this, please check the [official site and documentation](https://spark.apache.org/). 

We have used **Apache Spark 1.6.1**.

In order to work the Rest Service you have to upload (starting by ./python/ folder): 

- service.py
- NaiveBayesClassifier.py 
- ./model/*
- ./data/* 

**Start Service** 

```shell
source venv/bin/activate --serve-in-foreground nohup python service.py &
```

**Stop Service**
```shell
ps auxwww|grep -i 'python' 
kill PID
deactivate
```

Other files are used to generate images from  Microsoft FER+ dataset, calculate the  accuracy, precision, recall, f-measure and other test. We do not provide any kind of support about that files. 



## Open Source Libraries 

We use some Open Source libraries: 

- [Codeigniter PHP Framework](https://codeigniter.com/)
- [Bootstrap](http://getbootstrap.com/) 
- [Jquery](https://jquery.com/) 
- [Justified Gallery By @miromannino](https://github.com/miromannino/Justified-Gallery) 
- [Popper](https://popper.js.org/)



# Live Demo

Our live demo is not accessible anymore.
