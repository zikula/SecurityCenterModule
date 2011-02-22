<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */


class Categories_Controller_Ajax extends Zikula_Controller_Ajax
{

    protected function configureView()
    {
        Zikula_Controller::configureView();
    }

    /**
     * Resequence categories
     */
    public function resequence() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_EDIT));

        $data = json_decode(FormUtil::getPassedValue('data', null, 'post'), true);
        $cats = CategoryUtil::getSubCategories(1, true, true, true, true, true, '', 'id');

        foreach ($cats as $k => $cat) {
            $cid = $cat['id'];
            if(isset($data[$cid])) {
                $cats[$k]['sort_value'] = $data[$cid]['lineno'];
                $cats[$k]['parent_id'] = $data[$cid]['parent'];
                $obj = new Categories_DBObject_Category($cats[$k]);
                $obj->update();
            }
        }

        $result = array(
            'response' => true
        );
        return new Zikula_Response_Ajax($result);
    }

    public function edit() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_EDIT));

        $cid = FormUtil::getPassedValue('cid', 0);
        $mode = FormUtil::getPassedValue('mode', 'new');
        $parent = FormUtil::getPassedValue('parent', 1, 'post');
        $editCat  = '';

        $languages = ZLanguage::getInstalledLanguages();

        // indicates that we're editing
        if ($mode == 'edit')
        {
            if (!$cid) {
                return new Zikula_Response_Ajax_BadData($this->__('Error! Cannot determine valid \'cid\' for edit mode in \'Categories_admin_edit\'.'));
            }

            $category = new Categories_DBObject_Category();
            $editCat = $category->select($cid);
            $this->throwNotFoundUnless($editCat, $this->__('Sorry! No such item found.'));
        } else {
            // new category creation
            $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_ADD));

            if (FormUtil::getValidationErrors()) {
                $category = new Categories_DBObject_Category('V'); // need this for validation info
                $editCat  = $category->get();
            } else {
                // someone just pressen 'new' -> populate defaults
                $category = new Categories_DBObject_Category(); // need this for validation info
                $editCat['sort_value'] = '0';
                $editCat['parent_id'] = $parent;
            }
        }

        $attributes = isset($editCat['__ATTRIBUTES__']) ? $editCat['__ATTRIBUTES__'] : array();

        $this->view->setCaching(false);

        $this->view->assign('mode', $mode)
                   ->assign('category', $editCat)
                   ->assign('attributes', $attributes)
                   ->assign('languages', $languages)
                   ->assign('validation', $category->_objValidation);

        $result = array(
            'action' => $mode == 'new' ? 'add' : 'edit',
            'result' => $this->view->fetch('categories_adminajax_edit.tpl')
        );
        return new Zikula_Response_Ajax($result);
    }

    public function copy() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_ADD));

        $cid = FormUtil::getPassedValue('cid', null, 'post');
        $parent = FormUtil::getPassedValue('parent', null, 'post');

        $cat = new Categories_DBObject_Category(DBObject::GET_FROM_DB, $cid);
        // TODO - make sure new categories path will be unique - see ticket: 2847
        $cat->copy($parent);

        // need to find id of new category
        // co get categories with path like source category
        $cats = CategoryUtil::getCategoriesByPath($cat->getDataField('path'), 'id', 'path');

        // find the one with path equal soruce cat and highest id - it will be new root cat
        foreach($cats as $c) {
            if ($c['path'] == $cat->getDataField('path')) {
                $newRoot = $c['id'];
            }
        }

        $categories = CategoryUtil::getSubCategories($newRoot, true, true, true, true, true);
        $options = array(
            'nullParent' => $cat->getDataField('parent_id'),
            'withWraper' => false,
        );
        $node = CategoryUtil::getCategoryTreeJS((array)$categories, true, true, $options);

        $leafStatus = array(
            'leaf' => array(),
            'noleaf' => array()
        );
        foreach($categories as $c) {
            if($c['is_leaf']) {
                $leafStatus['leaf'][] = $c['id'];
            } else {
                $leafStatus['noleaf'][] = $c['id'];
            }
        }
        $result = array(
            'action' => 'copy',
            'cid' => $cid,
            'copycid' => $newRoot,
            'parent' => $parent,
            'node' => $node,
            'leafstatus' => $leafStatus,
            'result' => true
        );
        return new Zikula_Response_Ajax($result);
    }

    public function delete() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_DELETE));

        $cid = FormUtil::getPassedValue('cid', null, 'post');
        $cat = new Categories_DBObject_Category(DBObject::GET_FROM_DB, $cid);
        $cat->delete(true);

        $result = array(
            'action' => 'delete',
            'cid' => $cid,
            'result' => true
        );
        return new Zikula_Response_Ajax($result);
    }

    public function activate() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_EDIT));

        $cid = FormUtil::getPassedValue('cid', null, 'post');
        $cat = new Categories_DBObject_Category(DBObject::GET_FROM_DB, $cid);
        $cat->setDataField('status', 'A');
        $cat->update();

        $result = array(
            'action' => 'activate',
            'cid' => $cid,
            'result' => true
        );
        return new Zikula_Response_Ajax($result);
    }

    public function deactivate() {
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', ACCESS_EDIT));

        $cid = FormUtil::getPassedValue('cid', null, 'post');
        $cat = new Categories_DBObject_Category(DBObject::GET_FROM_DB, $cid);
        $cat->setDataField('status', 'I');
        $cat->update();

        $result = array(
            'action' => 'deactivate',
            'cid' => $cid,
            'result' => true
        );
        return new Zikula_Response_Ajax($result);
    }

    public function save()
    {
        $this->checkAjaxToken();
        $mode = FormUtil::getPassedValue('mode', 'new');
        $accessLevel = $mode == 'edit' ? ACCESS_EDIT : ACCESS_ADD;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Categories::', '::', $accessLevel));

        $result = array();

        $cat = new Categories_DBObject_Category();
        $cat->getDataFromInput ();

        if (!$cat->validate()) {
            // TODO - need to handle validation errors - see ticket: 2847
            return new Zikula_Response_Ajax_BadData('validation failed');
        }

        $attributes = array();
        $values = FormUtil::getPassedValue('attribute_value', 'POST');
        foreach (FormUtil::getPassedValue('attribute_name', 'POST') as $index => $name)
        {
            if (!empty($name)) {
                $attributes[$name] = $values[$index];
            }
        }

        $cat->setDataField('__ATTRIBUTES__', $attributes);

        if ($mode == 'edit') {
            // retrieve old category from DB
            $category = FormUtil::getPassedValue ('category', null, 'POST');
            $oldCat = new Categories_DBObject_Category(DBObject::GET_FROM_DB, $category['id']);

            // update new category data
            $cat->update();

            // since a name change will change the object path, we must rebuild it here
            if ($oldCat->getDataField('name') != $cat->getDataField('name')) {
                CategoryUtil::rebuildPaths ('path', 'name', $cat->getDataField('id'));
            }
        } else {
            $cat->insert();
            // update new category data
            $cat->update();
        }

        $categories = CategoryUtil::getSubCategories($cat->getDataField('id'), true, true, true, true, true);
        $options = array(
            'nullParent' => $cat->getDataField('parent_id'),
            'withWraper' => false,
        );
        $node = CategoryUtil::getCategoryTreeJS((array)$categories, true, true, $options);

        $leafStatus = array(
            'leaf' => array(),
            'noleaf' => array()
        );
        foreach($categories as $c) {
            if($c['is_leaf']) {
                $leafStatus['leaf'][] = $c['id'];
            } else {
                $leafStatus['noleaf'][] = $c['id'];
            }
        }

        $result = array(
            'action' => $mode == 'edit' ? 'edit' : 'add',
            'cid' => $cat->getDataField('id'),
            'parent' => $cat->getDataField('parent_id'),
            'node' => $node,
            'leafstatus' => $leafStatus,
            'result' => true
        );
        return new Zikula_Response_Ajax($result);
    }
}
