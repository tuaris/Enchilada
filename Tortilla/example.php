<?php
include '../Core/classes/EnchiladaLibrary.class.php';
include '../Core/classes/Configurable.class.php';
include 'classes/SimpleConfiguration.class.php';
include 'classes/SimpleInstaller.class.php';
	
?>
<html>
	<head>
		<title>Enchila Libraries 3.x - Tortilla Installation and Configuration Framework</title>
	<script>

	</script>
	<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
	</head>

	<body>
		<h2>Tortilla Configuration Framework Example</h2>
		<p>Load required libraries:</p>
		<pre class="prettyprint">
			include 'EnchiladaLibrary.class.php';
			include 'Configurable.class.php';
			include 'SimpleConfiguration.class.php';
		</pre>

		<h3>Design Your Configurable Object</h3>
		<p>Design your configurable entity by extending the 'Configurable' class</p>
		<pre class="prettyprint">
			class Connection extends Configurable {

			}
		</pre>
		
		<p>List the 'required' configuration options as a comma seperated list in a static variable named "Parameters".</p>
		<pre class="prettyprint">
			class Connection extends Configurable {
				protected static $Parameters = 'HOST,PORT,USER,PASS';
			}
		</pre>
		
		<p>Setup corresponding private/protected variables. Note: Future versions of this framework will do this for you.  For now
		 You have to do it your self.</p>
		<pre class="prettyprint">
			class Connection extends Configurable {
				protected static $Parameters = 'HOST,PORT,USER,PASS';
				
				protected $HOST;
				protected $PORT;
				protected $USER;
				protected $PASS;

				public function Open(){
					echo "Connecting to: {$this->HOST}:{$this->PORT} using {$this->USER}:{$this->PASS}";
				}
			}
		</pre>
		<?php
			class Connection extends Configurable {
				protected static $Parameters = 'HOST,PORT,USER,PASS';
				
				protected $HOST;
				protected $PORT;
				protected $USER;
				protected $PASS;

				public function Open(){
					echo "Connecting to: {$this->HOST}:{$this->PORT} using {$this->USER}:{$this->PASS}";
				}
			}
		?>
		<h3>Automaticy Generate a Configuration for your Object</h3>
		<p>Create a new SimpleConfiguration by passing your configurable's class name:</p>
		<pre class="prettyprint">
			$config = new SimpleConfiguration('Connection');
		</pre>
		<?php
			$config = new SimpleConfiguration('Connection');
		?>
		<p>The configuration options for your object can be obtained as an accosiative array.  Note that the options are
		automaticly prefixed with your class name.</p>
		<pre class="prettyprint">
			$options = $config->ListOptions();
			print_r($options);
		</pre>
		<pre class="prettyprint">
		<?php 
			$options = $config->ListOptions();
			print_r($options);
		?>
		</pre>
		<p>You can setup the options all at once:</p>
		<pre class="prettyprint">
			$options['CONNECTION_HOST'] = 'host.domain.tld';
			$options['CONNECTION_USER'] = 'pperson';
			$options['CONNECTION_PORT'] = '21';

			$config->Save($options);
		</pre>
		<?php
			$options['CONNECTION_HOST'] = 'host.domain.tld';
			$options['CONNECTION_USER'] = 'pperson';
			$options['CONNECTION_PORT'] = '21';
			$config->Save($options);
		?>
		<p>A simple validation feature is included to esusure all required paramaters are set</p>
		<pre class="prettyprint">
			// Returns an array containing missing options
			$missing_options = $config->Validate();
			print_r($missing_options);
		</pre>
		<pre class="prettyprint">
		<?php
			$missing_options = $config->Validate();
			print_r($missing_options);
		?>
		</pre>
		<p>Options can even be set and accessed directly:</p>
		<pre class="prettyprint">
			$config->CONNECTION_PASS = '1234';
			echo $config->CONNECTION_PASS;
		</pre>
		<pre class="prettyprint">
		<?php
			$config->CONNECTION_PASS = '1234';
			echo $config->CONNECTION_PASS;
		?>
		</pre>
		<p>Get the currently set options:</p>
		<pre class="prettyprint">
			// Returns an array with current options
			$current_options = $config->Load();
		</pre>
		<h3>Applying a Configuration to your Object</h3>
		<p>Create an instance of your object and apply the configuration:</p>
		<pre class="prettyprint">
			$my_connection = new Connection();
			$my_connection->setConfiguration($config);
		</pre>
		<?php
			$my_connection = new Connection();
			$my_connection->setConfiguration($config);
		?>
		<p>Your object is now automaticly configured for usage:</p>
		<pre class="prettyprint">
			$my_connection->Open();
		</pre>
		<pre class="prettyprint">
		<?php
			$my_connection->Open();
		?>
		</pre>
		<h2>Tortilla Installation Framework Example</h2>
		<p>Take things to the next level with the Installation framework.  First load the required library:</p>
		<pre class="prettyprint">
			include 'SimpleInstaller.class.php';
		</pre>
		<h3>Install a Configuration</h3>
		<p>Create a new installer object by specifying the location of the configuration fille it can read/write to:</p>
		<pre class="prettyprint">
			$installer = new SimpleInstaller('config.inc.php');
		</pre>
		<?php
			$installer = new SimpleInstaller('config.inc.php');
		?>
		<p>Save your object's options to persistant storage by 'installing' a configuration file:</p>
		<pre class="prettyprint">
			$installer->writeConfiguration($config);
		</pre>
		<p>What you should end up with is a new file with the following content:</p>
		<pre class="prettyprint">
&lt;?php
<?php
	$installer->writeConfiguration($config);
	echo $installer->getCode();
?>

?&gt;
		</pre>
		<h3>Put it all together</h3>
		<p>With the Tortilla Instalation and Configuration Framework you can load a configuration file
		with the required options in order to properly run your application.  Here is a full example:</p>
		<pre class="prettyprint">
			$app_installer = new SimpleInstaller('config.inc.php');
			$app_config = new SimpleConfiguration('Connection');
			$app_connection = new Connection();
			
			$app_config->Save($app_installer->readConfiguration());
			$app_connection->setConfiguration($app_config);
			$app_connection->Open();
		</pre>
		<pre class="prettyprint">
			<?php
			$app_installer = new SimpleInstaller('config.inc.php');
			$app_connection = new Connection();
			$app_config = new SimpleConfiguration($app_connection);

			$app_config->Save($app_installer->readConfiguration());
			$app_connection->setConfiguration($app_config);
			$app_connection->Open();
			?>
		</pre>
	</body>
</html>