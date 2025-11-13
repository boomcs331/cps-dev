<?php

class ActionHelper
{
    public static function renderDropdown($actions, $id = null)
    {
        if (empty($actions)) {
            return '';
        }

        $html = '<div class="dropdown">';
        $html .= '<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">';
        $html .= '<i class="fas fa-ellipsis-h"></i>';
        $html .= '</button>';
        $html .= '<ul class="dropdown-menu dropdown-menu-end">';
        
        foreach ($actions as $action) {
            if ($action['type'] === 'divider') {
                $html .= '<li><hr class="dropdown-divider"></li>';
                continue;
            }
            
            $url = $action['url'] ?? '#';
            if ($id && strpos($url, '{id}') !== false) {
                $url = str_replace('{id}', $id, $url);
            }
            
            $icon = isset($action['icon']) ? '<i class="' . $action['icon'] . ' me-2"></i>' : '';
            $class = $action['class'] ?? '';
            
            if ($action['type'] === 'modal') {
                $html .= '<li><button type="button" class="dropdown-item ' . $class . '" data-bs-toggle="modal" data-bs-target="' . $action['target'] . '">';
                $html .= $icon . $action['label'];
                $html .= '</button></li>';
            } else {
                $html .= '<li><a class="dropdown-item ' . $class . '" href="' . $url . '">';
                $html .= $icon . $action['label'];
                $html .= '</a></li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    public static function renderButtons($actions, $id = null, $size = 'sm')
    {
        if (empty($actions)) {
            return '';
        }

        $html = '<div class="btn-group btn-group-' . $size . '" role="group">';
        
        foreach ($actions as $action) {
            $url = $action['url'] ?? '#';
            if ($id && strpos($url, '{id}') !== false) {
                $url = str_replace('{id}', $id, $url);
            }
            
            $icon = isset($action['icon']) ? '<i class="' . $action['icon'] . '"></i>' : '';
            $btnClass = $action['class'] ?? 'btn-outline-secondary';
            $title = $action['title'] ?? $action['label'];
            
            if ($action['type'] === 'modal') {
                $html .= '<button type="button" class="btn ' . $btnClass . '" data-bs-toggle="modal" data-bs-target="' . $action['target'] . '" title="' . $title . '">';
                $html .= $icon;
                $html .= '</button>';
            } else {
                $html .= '<a class="btn ' . $btnClass . '" href="' . $url . '" title="' . $title . '">';
                $html .= $icon;
                $html .= '</a>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    public static function renderInline($actions, $id = null)
    {
        if (empty($actions)) {
            return '';
        }

        $html = '<div class="d-flex gap-2">';
        
        foreach ($actions as $action) {
            $url = $action['url'] ?? '#';
            if ($id && strpos($url, '{id}') !== false) {
                $url = str_replace('{id}', $id, $url);
            }
            
            $class = $action['class'] ?? 'text-primary';
            $title = $action['title'] ?? $action['label'];
            
            if ($action['type'] === 'modal') {
                $html .= '<button type="button" class="btn btn-link btn-sm p-0 ' . $class . '" data-bs-toggle="modal" data-bs-target="' . $action['target'] . '" title="' . $title . '">';
                $html .= $action['label'];
                $html .= '</button>';
            } else {
                $html .= '<a class="btn btn-link btn-sm p-0 ' . $class . '" href="' . $url . '" title="' . $title . '">';
                $html .= $action['label'];
                $html .= '</a>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
}