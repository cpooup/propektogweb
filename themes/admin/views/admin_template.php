<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>favicon.ico?v=<?php echo $site_version; ?>">

    <title><?php echo $page_title; ?></title>

    <?php if (isset($css_files) && is_array($css_files)) : ?>
        <?php foreach ($css_files as $css) : ?>
            <?php if ( ! is_null($css)) : ?>
                <link rel="stylesheet" href="<?php echo $css; ?>?v=<?php echo $site_version; ?>"><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="https://raw.github.com/scottjehl/Respond/master/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <?php // Fixed navbar ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only"><?php echo lang('core button toggle_nav'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <?php 
                        $img = './images/logo/logo-master';
                        $logo = '';
                        if(file_exists($img.'.gif')){
                            $logo =  base_url().$img.'.gif'; 
                        }else if(file_exists($img.'.png')){
                            $logo =  base_url().$img.'.png'; 
                        }else if(file_exists($img.'.jpg')){
                            $logo =  base_url().$img.'.jpg'; 
                        }else if(file_exists($img.'.jpeg')){
                            $logo =  base_url().$img.'.jpeg'; 
                        }
                    ?>
                    <img src="<?php echo $logo;?>" alt="logo" />
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <?php // Nav bar left ?>
                <?php echo $this->admin_nav; ?>
                <?php // Nav bar right ?>
                <ul class="nav navbar-nav navbar-right">
                    <?php if($this->config->item('master_sitename')!=$this->config->item('sitename')) : ?>
                        <?php 
                            $img = './images/logo/logo-'.$this->config->item('sitename');
                            $logo = '';
                            if(file_exists($img.'.gif')){
                                $logo =  base_url().$img.'.gif'; 
                            }else if(file_exists($img.'.png')){
                                $logo =  base_url().$img.'.png'; 
                            }else if(file_exists($img.'.jpg')){
                                $logo =  base_url().$img.'.jpg'; 
                            }else if(file_exists($img.'.jpeg')){
                                $logo =  base_url().$img.'.jpeg'; 
                            }
                            if(!empty($logo)){
                                echo '<li class="logo"><img src="'.$logo.'" alt="logo" /></li>';
                            }
                        ?>
                    <?php endif; ?>
                    <li><a data-toggle="modal" data-target="#myEdit" href="<?php echo base_url('admin/users/edit/'); ?>">
                        <?php 
                             $user = $this->session->userdata('logged_in');
                             echo sprintf(lang('admin label username'), $user['username']); 
                        ?>
                        </a>
                    </li>
                    <li><a href="<?php echo base_url('logout'); ?>"><?php echo lang('core button logout'); ?></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php // Main body ?>
    <div class="container">

        <div class="padding-override">
            <?php // Page title ?>
<!--            <div class="row">
                <h1><?php echo $page_title; ?></h1>
            </div>-->
            <?php if (isset($tabs)) : ?>
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach ($tabs as $tab) : ?>
                            <li role="presentation" class="<?php echo ($tab['active']) ? $tab['active'] : ''; ?>"><a href="<?php echo $tab['url']; ?>"><?php echo $tab['text']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php // Main controls ?>
            <div class="row text-right">
                <?php if (isset($controls)) : ?>
                    <br />
                    <?php foreach ($controls as $control) : ?>
                        <a class="btn <?php echo $control['bootstrap_button_class']; ?>" href="<?php echo $control['url']; ?>" title="<?php echo ($control['tooltip']) ? $control['tooltip'] : $control['text']; ?>" data-toggle="<?php echo ($control['data-toggle']) ? $control['data-toggle'] : ''; ?>" data-target="<?php echo ($control['data-target']) ? $control['data-target']: ''; ?>"><span class="glyphicon <?php echo $control['bootstrap_icon_class']; ?>"></span> <?php echo $control['text']; ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php // System messages ?>
        <?php if ($this->session->flashdata('message')) : ?>
            <div class="padding-override">
                <div class="row alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            </div>
        <?php elseif ($this->session->flashdata('error')) : ?>
            <div class="padding-override">
                <div class="row alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            </div>
        <?php elseif (validation_errors()) : ?>
            <div class="padding-override">
                <div class="row alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo validation_errors(); ?>
                </div>
            </div>
        <?php elseif ($this->error) : ?>
            <div class="padding-override">
                <div class="row alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->error; ?>
                </div>
            </div>
        <?php endif; ?>
         <?php if (isset($controls)) : ?>
        <hr />
        <?php endif; ?>
        <?php // Main content ?>
        <div class="padding-override">
            <?php echo $content; ?>

            <footer class="row footer text-muted"><br />Page rendered in <strong>{elapsed_time}</strong> seconds</footer>
        </div>

    </div>


    <?php if (isset($js_files) && is_array($js_files)) : ?>
        <?php foreach ($js_files as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript" src="<?php echo $js; ?>?v=<?php echo $site_version; ?>"></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
        <?php foreach ($js_files_i18n as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>                
<div class="modal fade" id="myModalColumn" tabindex="-1" role="dialog" aria-labelledby="myModalColumn" aria-hidden="true"></div>
<div class="modal fade" id="myEdit" tabindex="-1" role="dialog" aria-labelledby="myEditLabel" aria-hidden="true"></div>                
<div class="modal fade" id="myModalCompany" tabindex="-1" role="dialog" aria-labelledby="myEditLabel" aria-hidden="true"></div>                

</body>
</html>