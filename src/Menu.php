<?php 

namespace dynamikaweb\adaptive;

use Composer\InstalledVersions as Composer;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Html;
use Yii;

class Menu extends \yii\base\Widget 
{
    const NIVEL_ROOT = 'r';
    const NIVEL_SUB = 's';
    const NIVEL_URL = 'u';

    public $items = [];
    public $maxItems = 5;

    private $_asset;
    private $_key_cache;

    /**
     * Renders the menu.
     */
    public function run()
    {
        /** config widget */
        $this->_asset = MenuAsset::register($this->view);
        $this->_key_cache = $this->generateHash();
        
        /** retrieve content from cache */
        if (Yii::$app->cache->exists($this->_key_cache)) {
            echo Yii::$app->cache->get($this->_key_cache);
            return;
        }

        /** render widget */
        $content = $this->renderFile('forest', [
            'items' => $this->renderItems($this->normalizeItems(), self::NIVEL_ROOT),
            'id' => $this->getId(),
        ]);

        /** storage content in cache and print */
        Yii::$app->cache->set($this->_key_cache, $content);        
        echo $content;
    }

    /**
     * @return array
     */
    public function normalizeItems()
    {
        $newRoots = [];

        if (empty($this->items)) {
            return [];
        }

        // generate new roots
        foreach ($this->items as $oldRoot)
        {
            $oldRoot['items'] = ArrayHelper::getValue($oldRoot, 'items', []);
            $label = ArrayHelper::getValue($oldRoot, 'encode', true)? Html::encode($oldRoot['label']): $oldRoot['label'];
            $slug = Inflector::slug(strip_tags($oldRoot['label']));
            $target = ArrayHelper::getValue($oldRoot, 'target', '_self');
            $url = ArrayHelper::getValue($oldRoot, 'url', 'javascript:;');
            $newSubs = [];

            // automatic content root has a new sub menu
            if (!empty($oldRoot['content'])) {
                $url = 'javascript:;';
                $newSubs[] = [
                    'label' => $label,
                    'url' => $url,
                    'slug' => '_auto',
                    'content' => $oldRoot['content'],
                    'items' => []
                ];
            }

            // create sub menus
            foreach ($oldRoot['items'] as $oldSub) {
                // sub menu is a final link?
                if(empty($oldSub['items']) && empty($oldSub['content'])){ 
                    // last menu is no automatic or bigger
                    if((empty($newSubs) || (isset($newSubs[array_key_last($newSubs)]['items']) && count($newSubs[array_key_last($newSubs)]['items']) >= $this->maxItems)
                        || current($newSubs)['slug'] !== '_auto')) {
                        $newSubs[] = ['slug' => '_auto', 'url' => 'javascript:;'];
                    }
                    // add menu as link
                    $newSubs[array_key_last($newSubs)]['items'][] = [
                        'url' => ArrayHelper::getValue($oldSub, 'url', 'javascript:;'),
                        'target' => ArrayHelper::getValue($oldSub, 'target', '_self'),
                        'label' => ArrayHelper::getValue($oldSub, 'label', '???'),
                    ];
                    continue;
                }

                // add sub menu normal
                $newSubs[] = [
                    'slug' => Inflector::slug(ArrayHelper::getValue($oldSub, 'label', '_none')),
                    'url' => ArrayHelper::getValue($oldSub, 'url', 'javascript:;'),
                    'content' => ArrayHelper::getValue($oldSub, 'content', null),
                    'label' => ArrayHelper::getValue($oldSub, 'label', null),
                    'items' => []
                ];

                // add links
                foreach($oldSub['items'] as $item) {
                    // balance quantity of links per sub menu
                    if (count($newSubs[array_key_last($newSubs)]['items']) >= $this->maxItems) {
                        $newSubs[] = ['slug' => '_auto', 'url' => 'javascript:;'];
                    }
                    
                    // add link to sub menu
                    $newSubs[array_key_last($newSubs)]['items'][] = [
                        'url' => ArrayHelper::getValue($item, 'url', 'javascript:;'),
                        'target' => ArrayHelper::getValue($item, 'target', '_self'),
                        'label' => ArrayHelper::getValue($item, 'label', '???')
                    ];
                }
            }

            $newRoots[] = [
                'label' => $label,
                'slug' => $slug,
                'target' => $target,
                'url' => $url,
                'items' => $newSubs
            ];
        }

        return $newRoots;
    }

    /**
     * @param array
     * @return string
     */
    protected function renderItems($items, $nivel)
    {
        switch ($nivel)
        {
            case self::NIVEL_ROOT: {
                return implode("\n", array_map(fn($item) => $this->renderFile('root', [
                    'items' => empty($item['items'])? null: Html::tag('span', $this->renderItems($item['items'], self::NIVEL_SUB), ['class' => 'dynamika-menu-span-items']),
                    'target' => $item['target'],
                    'label' => $item['label'],
                    'slug' => $item['slug'],
                    'url' => $item['url']
                ]), 
                    $items
                ));
            }
            case self::NIVEL_SUB: {
                return implode("\n", array_map(fn($item) => $this->renderFile('sub', [
                    'items' => $this->renderItems($item['items'], self::NIVEL_URL),
                    'content' => empty($item['content'])? null: Html::tag('p', $item['content']),
                    'label' => empty($item['label'])? null: Html::a("<h3>{$item['label']}</h3>", $item['url']),                    
                    'slug' => $item['slug']
                ]), 
                    $items
                ));
            }
            case self::NIVEL_URL: {
                return implode("\n", array_map(fn($item) => $this->renderFile('link', [
                    'target' => $item['target'],
                    'label' => $item['label'],
                    'url' => $item['url']
                ]), 
                    $items
                ));
            }
        }
    }

    /**
     * @see BaseYii::t()
     * @return string
     */
    public function renderFile($view, $params = [])
    {
        $placeholders = [];

        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return strtr(
            $this->view->renderFile($this->_asset->getFile($view)),
            $placeholders
        );
    }

    /**
     * @return string
     */
    public function generateHash()
    {
        return strtr('menu-{md5}-{version}', [
            '{version}' => Composer::getVersion('dynamikaweb/yii2-adaptive-menu'),
            '{md5}' => md5(json_encode($this->items)),
        ]);
    }
}
