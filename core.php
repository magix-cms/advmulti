<?php
/**
 * Class plugins_test_core
 * Fichier pour les plugins core
 */
class plugins_advmulti_core extends plugins_advmulti_admin
{
    /**
     * @var object
     */
    protected
        $modelPlugins,
        $plugins;

    /**
     * @var int
     */
    public
        $mod_edit;

    /**
     * @var string
     */
    public
        $mod_action,
        $plugin;

    /**
     * plugins_advmulti_core constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelPlugins = new backend_model_plugins();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();

        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
        if (http_request::isRequest('mod_action')) $this->mod_action = $formClean->simpleClean($_REQUEST['mod_action']);
        if (http_request::isGet('mod_edit')) $this->mod_edit = $formClean->numeric($_GET['mod_edit']);
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function getIcoNamesFromLess(string $filePath): array {
        $this->logger = new debug_logger(MP_LOG_DIR);
        $icoNames = [];
        $prefix = 'ico ico-';

        if (!file_exists($filePath)) {
            $this->logger->log('php', 'error', 'An error has occured : ' . "Le fichier '$filePath' n'existe pas. Veuillez vérifier le chemin.", debug_logger::LOG_MONTH);
            return $icoNames;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            $this->logger->log('php', 'error', 'An error has occured : ' . "Impossible de lire le fichier '$filePath'. Vérifiez les permissions.", debug_logger::LOG_MONTH);
            return $icoNames;
        }

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Remplace str_starts_with() par substr() (str_starts_with compatible PHP8)
            if (substr($trimmedLine, 0, 5) === '@ico-') {
                // ----------------------------------

                // Utilise une expression régulière pour extraire le nom de l'icône
                if (preg_match('/@ico-([a-zA-Z0-9_-]+):/', $trimmedLine, $matches)) {
                    $icoNames[] = $prefix . $matches[1];
                }
            }
        }

        return $icoNames;
    }
    /**
     *
     */
    protected function runAction()
    {
        switch ($this->mod_action) {
            case 'add':
            case 'edit':
                if( isset($this->advmulti) && !empty($this->advmulti) ) {
                    $notify = 'update';

                    if (!isset($this->advmulti['id'])) {
                        $this->add([
                            'type' => 'advmulti',
                            'data' => [
                                'icon_advmulti' => $this->advmulti['icon_advmulti'],
                                'module' => $this->controller,
                                'id_module' => $this->edit ?: NULL
                            ]
                        ]);

                        $lastadvmulti = $this->getItems('lastadvmulti', null,'one',false);
                        $this->advmulti['id'] = $lastadvmulti['id_advmulti'];
                        $notify = 'add_redirect';
                    }else{
                        $this->upd([
                            'type' => 'icon',
                            'data' => [
                                'icon_advmulti' => $this->advmulti['icon_advmulti'],
                                'id' => $this->advmulti['id']
                            ]
                        ]);
                    }

                    foreach ($this->advmulti['content'] as $lang => $advmulti) {
                        $advmulti['id_lang'] = $lang;
                        $advmulti['blank_advmulti'] = (!isset($advmulti['blank_advmulti']) ? 0 : 1);
                        $advmulti['published_advmulti'] = (!isset($advmulti['published_advmulti']) ? 0 : 1);
                        $advmultiLang = $this->getItems('advmultiContent',['id' => $this->advmulti['id'],'id_lang' => $lang],'one',false);

                        if($advmultiLang) $advmulti['id'] = $advmultiLang['id_advmulti_content'];
                        else $advmulti['id_advmulti'] = $this->advmulti['id'];

                        $config = ['type' => 'advmultiContent', 'data' => $advmulti];
                        $advmultiLang ? $this->upd($config) : $this->add($config);
                    }
                    $this->message->json_post_response(true,$notify);
                }
                else {
                    $this->modelLanguage->getLanguage();
                    $settings = $this->template->settings;
                    $theme = $settings['theme'];
                    $lessFilePath = component_core_system::basePath().'skin/'.$theme.'/css/icofont/variables.less';
                    if(file_exists($lessFilePath)){
                        $iconNames = $this->getIcoNamesFromLess($lessFilePath);
                        $this->template->assign('iconnames', $iconNames);
                    }
                    if(isset($this->mod_edit)) {
                        $collection = $this->getItems('advmultiContent',$this->mod_edit,'all',false);
                        $setEditData = $this->setItemadvmultiData($collection);
                        $this->template->assign('advmulti', $setEditData[$this->mod_edit]);
                    }

                    $this->template->assign('edit',$this->mod_action === 'edit');
                    $this->modelPlugins->display('mod/edit.tpl');
                }
                break;
            case 'delete':
                if(isset($this->id) && !empty($this->id)) {
                    $this->del([
                        'type' => 'advmulti',
                        'data' => ['id' => $this->id]
                    ]);
                }
                break;
            case 'order':
                if (isset($this->advmulti) && is_array($this->advmulti)) {
                    $this->order('home');
                }
                break;
        }
    }

    /**
     *
     */
    protected function adminList()
    {
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
        $this->getItems('advmultis',['lang' => $defaultLanguage['id_lang'], 'module' => $this->controller, 'id_module' => $this->edit ?: NULL],'all');
        $assign = [
            'id_advmulti',
            'url_advmulti' => ['title' => 'name'],
            'icon_advmulti' => ['type' => 'bin', 'input' => null, 'class' => ''],
            'title_advmulti' => ['title' => 'name'],
            'desc_advmulti' => ['title' => 'name']
        ];
        $this->data->getScheme(['mc_advmulti', 'mc_advmulti_content'], ['id_advmulti', 'url_advmulti', 'icon_advmulti','title_advmulti','desc_advmulti'], $assign);
        $this->modelPlugins->display('mod/index.tpl');
    }

    /**
     * Execution du plugin dans un ou plusieurs modules core
     */
    public function run() {
        if(isset($this->controller)) {
            switch ($this->controller) {
                case 'about':
                    $extends = $this->controller.(!isset($this->action) ? '/index.tpl' : '/pages/edit.tpl');
                    break;
                case 'category':
                case 'product':
                    $extends = 'catalog/'.$this->controller.'/edit.tpl';
                    break;
                case 'news':
                case 'catalog':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                case 'pages':
                case 'home':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                default:
                    $extends = 'index.tpl';
            }
            $this->template->assign('extends',$extends);
            if(isset($this->mod_action)) {
                $this->runAction();
            }
            else {
                $this->adminList();
            }
        }
    }
}