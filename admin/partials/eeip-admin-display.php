<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://veebiehitus.ee
 * @since      1.0.0
 *
 * @package    Eeip
 * @subpackage Eeip/admin/partials
 */

 if(isset($_GET['refresh_token'])){
   update_option('gtoken','');
   update_option('gfile','');
 }
 $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip';
?>



<?php


if(isset($_GET['type']) && strlen(get_option('gfile')) > 1){
  function generateAlphabet($na) {
         $sa = "";
         while ($na >= 0) {
             $sa = chr($na % 26 + 65) . $sa;
             $na = floor($na / 26) - 1;
         }
         return $sa;
     }

     $alphabet = Array();
     for ($na = 0; $na < 125; $na++) {
         $alphabet[]=generateAlphabet($na);
     }
  if(isset($_GET['update_cols']) && isset($_GET['sys_num'])){

    require_once dirname(__FILE__).'/google-api-php-client-2.1.0_PHP54/vendor/autoload.php';
        settings_fields($this->plugin_name);
       $options = get_option($this->plugin_name);
       $Client_id = $options['Client_id'];
       $Client_secret = $options['Client_secret'];
       $Email = $options['Email'];
       $AppName = $options['AppName'];
       $file_id = get_option('gfile');
       $client = new Google_Client();
       $client->setApplicationName($AppName);
       $client->setClientId($Client_id);
       $client->setClientSecret($Client_secret);
       $client->setScopes(array(
         Google_Service_Drive::DRIVE, Google_Service_Sheets::SPREADSHEETS ));
       $client->setSubject($Email);
       $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip';
       $client->setRedirectUri($redirect_uri);
       $tokens = get_option('gtoken');
       $client->setAccessToken($tokens);
       $client->refreshToken($tokens["refresh_token"]);
       $service = new Google_Service_Sheets($client);
       $spreadsheetId = get_option('gfile');
    $ranges = array(
      'A:'.$alphabet[intval($_GET['update_cols'])]
    );
    //var_dump($ranges);
    $params = array(
      'ranges' => $ranges
    );
    $result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
    var_dump($result[0]['values']);
    $x = 0;
    $updated_values = 0;
    $posts_added = 0;
    $posts_delete = 0;
    foreach ($result[0]['values'] as &$value) {
      if($x == 0){
        $header = $value;
        $x++;
        continue;
      }elseif($x == 1){
        $types = $value;
        $x++;
        continue;
      }else{
        $y = 0;
        $id = "";
        foreach($header as $key => &$value1){
          if($y < intval($_GET['sys_num'])-1){
            $output_post = array_slice($value, 0, intval($_GET['sys_num'])-1);
            $output_header = array_slice($header, 0, intval($_GET['sys_num'])-1);
            foreach($output_header as $key => $value13){
              if($types[$key] == 'integer'){
                $post_data[$value13] = intval($output_post[$key]);
              }elseif($types[$key] == 'string'){
                $post_data[$value13] = $output_post[$key];
              }elseif($types[$key] == 'array'){
                $post_data[$value13] = unserialize($output_post[$key]);
              }else{
                $post_data[$value13] =  $output_post[$key];
              }
            }
            if(intval($value[0]) > 0 && $y == 0){
              $id = intval($value[0]);
              wp_update_post( $post_data ,$error);
              $y++;
               continue;
              //var_dump($error);
            }elseif(intval($value[0])  == 0 && $y == 0){
              $post_data['post_type'] = $_GET['type'];
              $id = wp_insert_post($post_data);
              //var_dump($id);
              $posts_added++;
              $y++;
              continue;
            }elseif(intval($value[0]) < 0 && $y == 0){
              wp_delete_post( abs($value[0]), 'true' );
              $posts_deleted++;
              $y++;
              continue;
            }
            $y++;
             continue;
          }else{
            if($types[$y] == 'integer'){
              update_post_meta($id, $header[$y], intval($value[$y]));
            }elseif($types[$y] == 'string'){
              update_post_meta($id, $header[$y], $value[$y]);
            }elseif($types[$y] == 'array'){
              update_post_meta($id, $header[$y], unserialize($value[$y]));
            }else{
              update_post_meta($id, $header[$y], $value[$y]);
            }
              $updated_values++;
              $y++;
              continue;
          }
          $y++;
          continue;
        }
      }
      $x++;
    }

    echo '<h3>Update complete! Results: Inserted posts count:'.$posts_added.' And updated values count:'.$updated_values.' . Deleted posts: '.$posts_delete.'</h3>';
  }

  ?>
  <div class="wrap">


      <form method="post" name="cleanup_options" action="options.php">
        <?php if(strlen(get_option('gtoken')["refresh_token"]) > 1 && strlen(get_option('gfile')) > 1): ?>
          <!-- menu start -->
        <br>
        <style>
        .myButton {
	-moz-box-shadow: 0px 10px 14px -7px #3e7327;
	-webkit-box-shadow: 0px 10px 14px -7px #3e7327;
	box-shadow: 0px 10px 14px -7px #3e7327;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #77b55a), color-stop(1, #72b352));
	background:-moz-linear-gradient(top, #77b55a 5%, #72b352 100%);
	background:-webkit-linear-gradient(top, #77b55a 5%, #72b352 100%);
	background:-o-linear-gradient(top, #77b55a 5%, #72b352 100%);
	background:-ms-linear-gradient(top, #77b55a 5%, #72b352 100%);
	background:linear-gradient(to bottom, #77b55a 5%, #72b352 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77b55a', endColorstr='#72b352',GradientType=0);
	background-color:#77b55a;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
	border:1px solid #4b8f29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:13px;
	font-weight:bold;
	padding:6px 12px;
	text-decoration:none;
	text-shadow:0px 1px 0px #5b8a3c;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #72b352), color-stop(1, #77b55a));
	background:-moz-linear-gradient(top, #72b352 5%, #77b55a 100%);
	background:-webkit-linear-gradient(top, #72b352 5%, #77b55a 100%);
	background:-o-linear-gradient(top, #72b352 5%, #77b55a 100%);
	background:-ms-linear-gradient(top, #72b352 5%, #77b55a 100%);
	background:linear-gradient(to bottom, #72b352 5%, #77b55a 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#72b352', endColorstr='#77b55a',GradientType=0);
	background-color:#72b352;
}
.myButton:active {
	position:relative;
	top:1px;
}


        select {
          background:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50px' height='50px'><polyline points='46.139,15.518 25.166,36.49 4.193,15.519'/></svg>");
          background-color:#3498DB;
          background-repeat:no-repeat;
          background-position: right 10px top 9px;
          background-size: 16px 16px;
          color:white;
          padding:12px;
          width:auto;
          font-family:arial,tahoma;
          font-size:16px;
          font-weight:bold;
          color:#fff;
          text-align:center;
          text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);
          border-radius:3px;
          -webkit-border-radius:3px;
          -webkit-appearance: none;
          border:0;
          outline:0;
          -webkit-transition:0.3s ease all;
             -moz-transition:0.3s ease all;
              -ms-transition:0.3s ease all;
               -o-transition:0.3s ease all;
                  transition:0.3s ease all;
        }

        #blue {
          background-color:#3498DB;
        }

        #blue:hover {
          background-color:#2980B9;
        }

        #green {
          background-color:#2ECC71;
        }

        #green:hover {
          background-color:#27AE60;
        }

        #red {
          background-color:#E74C3C;
        }

        #red:hover {
          background-color:#C0392B;
        }

        select:focus, select:active {
          border:0;
          outline:0;
        }
        </style>
        <nav style="margin-bottom:10px; display:inline-block;">

            <select id="blue" style="inline-block" >
              <?php if(!isset($_GET['type'])): ?>
              <option value="" selected>Select Post Type</option>
            <?php endif; ?>
                <?php
                $args = array(
                );

                $output = 'objects'; // 'names' or 'objects' (default: 'names')
                $operator = 'and'; // 'and' or 'or' (default: 'and')

                $post_types = get_post_types( $args, $output, $operator );
                 ?>
                <?php foreach($post_types as $post_type): ?>
                <option <?php if(isset($_GET['type']) && $post_type->name == $_GET['type']){echo 'selected';} ?> id="<?php echo $post_type->name; ?>" value="<?php echo $redirect_uri.'&type='.$post_type->name; ?>"><?php echo $post_type->labels->name; ?></option>
              <?php endforeach; ?>
            </select>
        </nav>
        <script>
            jQuery(function(){
              // bind change event to select
              jQuery('#blue').on('change', function () {
                  var url = jQuery(this).val(); // get selected value
                  if (url) { // require a URL
                      window.location = url; // redirect
                  }
                  return false;
              });
            });
      </script>
        <!-- menu end -->
        <?php endif; ?>
   <?php
         function generate_foods_meta_keys(){
          global $wpdb;
          $post_type = $_GET['type'];
          $query = "
              SELECT DISTINCT($wpdb->postmeta.meta_key)
              FROM $wpdb->posts
              LEFT JOIN $wpdb->postmeta
              ON $wpdb->posts.ID = $wpdb->postmeta.post_id
              WHERE $wpdb->posts.post_type = '%s'
              AND $wpdb->postmeta.meta_key != ''
              AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)'
              AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
          ";
          $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
          //set_transient('foods_meta_keys', $meta_keys, 60*60*24); # create 1 Day Expiration
          return $meta_keys;
      }
      function get_foods_meta_keys(){
          $meta_keys = generate_foods_meta_keys();
          return $meta_keys;
      }


require_once dirname(__FILE__).'/google-api-php-client-2.1.0_PHP54/vendor/autoload.php';
    settings_fields($this->plugin_name);
   $options = get_option($this->plugin_name);
   $Client_id = $options['Client_id'];
   $Client_secret = $options['Client_secret'];
   $Email = $options['Email'];
   $AppName = $options['AppName'];
   $file_id = get_option('gfile');

   $client = new Google_Client();
   $client->setApplicationName($AppName);
   $client->setClientId($Client_id);
   $client->setClientSecret($Client_secret);
   $client->setScopes(array(
     Google_Service_Drive::DRIVE, Google_Service_Sheets::SPREADSHEETS ));
   $client->setSubject($Email);
   $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip';
   $client->setRedirectUri($redirect_uri);
   $tokens = get_option('gtoken');
   $client->setAccessToken($tokens);
   $client->refreshToken($tokens["refresh_token"]);
   $service = new Google_Service_Sheets($client);
   $spreadsheetId = get_option('gfile');
$header1 = get_foods_meta_keys();
    $args = array(
        'post_type' => $_GET['type'],
        'posts_per_page' => -1,
        'post_status' => 'any',
        'numberposts' => -1
        );
    $cpts = new WP_Query($args);
$system = 0;
$x = 0;
$types = array();
    if($cpts->have_posts()) : while($cpts->have_posts() ) : $cpts->the_post();
        $content = array();

        foreach((array)get_post(get_the_ID()) as $key => $value){
          if($x == 0){
            $header2[] = $key;
            $system++;
          }
          $content[] = $value;
          $types[$key] = gettype($value);
        }
        foreach ($header1 as &$value) {
          if(is_array(get_post_meta( $content[0], $value, true ))){
            $content[] = serialize(get_post_meta( $content[0], $value, true ));
            $types[$value] = 'array';
          }else{
           $content[] = get_post_meta( $content[0], $value, true );
           $types[$value] = gettype($value);
          }
        }
        $values1[] = $content;
        $x++;
    endwhile; endif;
    foreach($types as $key => $val){
      $types2[] = $val;
    }
    $header = array_merge($header2, $header1);

    $values = array(
            $header,
            $types2,
        );
        $values = array_merge($values, $values1);
            $alphabet = Array();
              for ($na = 0; $na < 125; $na++) {
              $alphabet[]=generateAlphabet($na);
              }
        $data = array();
        $range = $alphabet[0].':'.$alphabet[count($header)];
        $data[] = new Google_Service_Sheets_ValueRange(array(
          'range' => $range,
          'values' => $values
        ));

        $body = new Google_Service_Sheets_BatchUpdateValuesRequest(array(
          'valueInputOption' => 'USER_ENTERED',
          'data' => $data
        ));
        $range = $alphabet[0].':'.$alphabet[99];
        $body2 = new Google_Service_Sheets_ClearValuesRequest(array(
            'range' => $range,
          )
        );
        $result1 = $service->spreadsheets_values->clear($spreadsheetId,$range, $body2);
        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);

    ?>
          <a style="float:right;font-size:16px;font-weight:bold; margin-bottom:10px;" class="myButton" href="<?php echo $redirect_uri.'&update_cols='.count($header).'&sys_num='.$system.'&type='.$_GET['type']; ?>">Save changes to Database</a>

          <iframe width="100%" height="600px" src="https://docs.google.com/spreadsheets/d/<?php echo $file_id; ?>/edit?widget=true&amp;headers=false"></iframe>

          <?php submit_button('Save all changes ', 'primary','submit', TRUE); ?>
      </form>

  </div><?php
}else{

       if (isset($_GET['code']) && strlen(get_option('gfile')) < 1) {
          $options = get_option($this->plugin_name);
          $Client_id = $options['Client_id'];
          $Client_secret = $options['Client_secret'];
          $Email = $options['Email'];
          $AppName = $options['AppName'];
              if(strlen($Client_secret) > 1 && strlen($Client_id) > 1 && strlen($AppName) > 1 && strlen($_GET['code']) > 1 && strlen($Email) > 1 ){
                  require_once dirname(__FILE__).'/google-api-php-client-2.1.0_PHP54/vendor/autoload.php';
                  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip';
                  $client = new Google_Client();
                  $client->setApplicationName($AppName);
                  $client->setClientId($Client_id);
                  $client->setClientSecret($Client_secret);
                  $client->setRedirectUri($redirect_uri);
                  $client->setSubject($Email);
                  $client->setAccessType('offline');
                  if(strlen(get_option('gtoken')['access_token']) < 1){
                    $client->authenticate($_GET['code']);
                    update_option('gtoken',$client->getAccessToken());
                  }else{
                    $client->setAccessToken(get_option('gtoken')['refresh_token']);
                  }
                  $driveService = new Google_Service_Drive($client);
                  $fileMetadata = new Google_Service_Drive_DriveFile(array(
                  'name' => $AppName.' Post Type export',
                  'mimeType' => 'application/vnd.google-apps.spreadsheet'));
                  var_dump($driveService->files->create($fileMetadata, array(
                  'fields' => 'id')));
                  $file = $driveService->files->create($fileMetadata, array(
                  'fields' => 'id'));
                  $permission = new Google_Service_Drive_Permission();
                  $permission->setRole( 'writer' );
                  $permission->setType( 'anyone' );
                  update_option('gfile',$file->id);
                  $driveService->permissions->create( $file->id,  $permission , array('fields' => 'id'));
                  echo '<script>alert("File has been created to '.$Email.' Google Drive, and it has been shared with everyone who has link. You can start editing post types.");</script>';
               }
      }
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>



    <form method="post" name="cleanup_options" action="options.php">
      <?php if(strlen(get_option('gtoken')["refresh_token"]) > 1 && strlen(get_option('gfile')) > 1): ?>
        <!-- menu start -->
      <br>
      <style>
      select {
        background:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50px' height='50px'><polyline points='46.139,15.518 25.166,36.49 4.193,15.519'/></svg>");
        background-color:#3498DB;
        background-repeat:no-repeat;
        background-position: right 10px top 9px;
        background-size: 16px 16px;
        color:white;
        padding:12px;
        width:auto;
        font-family:arial,tahoma;
        font-size:16px;
        font-weight:bold;
        color:#fff;
        text-align:center;
        text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);
        border-radius:3px;
        -webkit-border-radius:3px;
        -webkit-appearance: none;
        border:0;
        outline:0;
        -webkit-transition:0.3s ease all;
      	   -moz-transition:0.3s ease all;
      	    -ms-transition:0.3s ease all;
      	     -o-transition:0.3s ease all;
      	        transition:0.3s ease all;
      }

      #blue {
        background-color:#3498DB;
      }

      #blue:hover {
        background-color:#2980B9;
      }

      #green {
        background-color:#2ECC71;
      }

      #green:hover {
        background-color:#27AE60;
      }

      #red {
        background-color:#E74C3C;
      }

      #red:hover {
        background-color:#C0392B;
      }

      select:focus, select:active {
        border:0;
        outline:0;
      }
      </style>
      <nav style="margin-bottom:10px;display:inline-block;">
          <select id="blue" style="" >
            <option selected>Select Post Type</option>
              <?php
              $args = array(
              );

              $output = 'objects'; // 'names' or 'objects' (default: 'names')
              $operator = 'and'; // 'and' or 'or' (default: 'and')

              $post_types = get_post_types( $args, $output, $operator );
               ?>
              <?php foreach($post_types as $post_type): ?>
              <option id="<?php echo $post_type->name; ?>" value="<?php echo $redirect_uri.'&type='.$post_type->name; ?>"><?php echo $post_type->labels->name; ?></option>
            <?php endforeach; ?>
          </select>
      </nav>
      <!-- menu end -->
      <?php endif; ?>
      <script>
          jQuery(function(){
            // bind change event to select
            jQuery('#blue').on('change', function () {
                var url = jQuery(this).val(); // get selected value
                if (url) { // require a URL
                    window.location = url; // redirect
                }
                return false;
            });
          });
    </script>
 <?php settings_fields($this->plugin_name);
$options = get_option($this->plugin_name);
$Client_id = $options['Client_id'];
$Client_secret = $options['Client_secret'];
$Email = $options['Email'];
$AppName = $options['AppName'];

if(strlen($Email) > 1 && strlen($Client_secret) > 1 && strlen($Client_id) > 1 && strlen($AppName) > 1 && !isset($_GET['code']) && strlen(get_option('gtoken')['access_token']) < 1){
  require_once dirname(__FILE__).'/google-api-php-client-2.1.0_PHP54/vendor/autoload.php';

  $client = new Google_Client();
  $client->setApplicationName($AppName);
  $client->setClientId($Client_id);
  $client->setClientSecret($Client_secret);
  $client->setAccessType("offline");
  $client->setApprovalPrompt ("force");
   $client->setScopes(array(
     Google_Service_Drive::DRIVE, Google_Service_Sheets::SPREADSHEETS ));
  $client->setSubject($Email);
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip';
  $client->setRedirectUri($redirect_uri);
  $authUrl = $client->createAuthUrl();
  ?>
  <a href="<?php echo $authUrl; ?>">Activate Google Drive Account!</a>
  <?php
  header('location:'.$authUrl);
}
  ?>
        <?php if(strlen(get_option('gfile')) > 1): ?>
        <fieldset>
                    <fieldset>
                        <p style="color:green;">Plugin has been activated and file has been created. File link : <a target="_blank" href="https://docs.google.com/spreadsheets/d/<?php echo get_option('gfile'); ?>/edit?widget=true&amp;headers=false">Google Spreadsheet</a></p>
                    </fieldset>
                    <?php
                    if(strlen(get_option('gfile')) > 1 && strlen(get_option('gtoken')['access_token']) > 1){
                      $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?page=eeip&refresh_token=1';
                        echo '<a href="'.$redirect_uri.'" >Refresh Auth Token and create new file</a>';
                    }

                     ?>
        </fieldset>
      <?php endif; ?>
        <fieldset>
                    <fieldset>
                        <p>Add your Google Developers Console Application Name : </p>
                        <legend class="screen-reader-text"><span><?php _e('Choose your prefered cdn provider', $this->plugin_name); ?></span></legend>
                        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-AppName" name="<?php echo $this->plugin_name; ?>[AppName]" value="<?php if(!empty($AppName)) echo $AppName; ?>"/>
                    </fieldset>
        </fieldset>
        <fieldset>
                    <fieldset>
                        <p>Add your Google Drive Email : </p>
                        <legend class="screen-reader-text"><span><?php _e('Choose your prefered cdn provider', $this->plugin_name); ?></span></legend>
                        <input type="email" class="regular-text" id="<?php echo $this->plugin_name; ?>-Email" name="<?php echo $this->plugin_name; ?>[Email]" value="<?php if(!empty($Email)) echo $Email; ?>"/>
                    </fieldset>
        </fieldset>
        <fieldset>
                    <fieldset>
                        <p>Add your Google API Client ID : </p>
                        <legend class="screen-reader-text"><span><?php _e('Choose your prefered cdn provider', $this->plugin_name); ?></span></legend>
                        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-Client_id" name="<?php echo $this->plugin_name; ?>[Client_id]" value="<?php if(!empty($Client_id)) echo $Client_id; ?>"/>
                    </fieldset>
        </fieldset>
        <fieldset>
                    <fieldset>
                        <p>Add your Google API Client secret : </p>
                        <legend class="screen-reader-text"><span><?php _e('Choose your prefered cdn provider', $this->plugin_name); ?></span></legend>
                        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-Client_secret" name="<?php echo $this->plugin_name; ?>[Client_secret]" value="<?php if(!empty($Client_secret)) echo $Client_secret; ?>"/>
                    </fieldset>
        </fieldset>

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>


    </form>

</div>
<?php } ?>
