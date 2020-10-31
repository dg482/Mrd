<?php

namespace Dg482\Mrd\Resource\Actions;

/**
 * Class ResourceAction
 * @package Dg482\Mrd\Resource\Actions
 */
abstract class ResourceAction
{
    /** @var string */
    protected string $action = '';

    /** @var string */
    protected string $actionUrl = '';

    /** @var string */
    protected string $icon = 'action';

    /** @var string */
    protected string $text = '';

    /** @var bool */
    protected bool $confirm = false;

    /** @var string */
    protected string $confirmTitle = 'Требуется подтверждение действия';

    /** @var string */
    protected string $confirmMessage = 'Вы уверены что следует продолжать?)';

    /** @var string */
    protected string $confirmOkText = 'Да';

    /** @var string */
    protected string $confirmCancelText = 'Нет';

    /** @var string */
    protected string $confirmIcon = 'exclamation-circle';

    /** @var string */
    protected string $confirmOkType = 'primary';

    /** @var string */
    protected string $form = 'default';

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param  string  $action
     * @return ResourceAction
     */
    public function setAction(string $action): ResourceAction
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getActionUrl(): string
    {
        return $this->actionUrl;
    }

    /**
     * @param  string  $actionUrl
     * @return ResourceAction
     */
    public function setActionUrl(string $actionUrl): ResourceAction
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param  string  $icon
     * @return ResourceAction
     */
    public function setIcon(string $icon): ResourceAction
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param  string  $text
     * @return ResourceAction
     */
    public function setText(string $text): ResourceAction
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirm(): bool
    {
        return $this->confirm;
    }

    /**
     * @return array|string[]
     */
    public function getConfirm(): array
    {
        return [
            'title' => $this->getConfirmTitle(),
            'message' => $this->getConfirmMessage(),
            'icon' => $this->getConfirmIcon(),
            'okText' => $this->getConfirmOkText(),
            'okType' => $this->getConfirmOkType(),
            'cancelText' => $this->getConfirmCancelText(),
        ];
    }

    /**
     * @param  bool  $confirm
     * @return ResourceAction
     */
    public function setConfirm(bool $confirm): ResourceAction
    {
        $this->confirm = $confirm;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmTitle(): string
    {
        return $this->confirmTitle;
    }

    /**
     * @param  string  $confirmTitle
     * @return ResourceAction
     */
    public function setConfirmTitle(string $confirmTitle): ResourceAction
    {
        $this->confirmTitle = $confirmTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmMessage(): string
    {
        return $this->confirmMessage;
    }

    /**
     * @param  string  $confirmMessage
     * @return ResourceAction
     */
    public function setConfirmMessage(string $confirmMessage): ResourceAction
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmOkText(): string
    {
        return $this->confirmOkText;
    }

    /**
     * @param  string  $confirmOkText
     * @return ResourceAction
     */
    public function setConfirmOkText(string $confirmOkText): ResourceAction
    {
        $this->confirmOkText = $confirmOkText;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmCancelText(): string
    {
        return $this->confirmCancelText;
    }

    /**
     * @param  string  $confirmCancelText
     * @return ResourceAction
     */
    public function setConfirmCancelText(string $confirmCancelText): ResourceAction
    {
        $this->confirmCancelText = $confirmCancelText;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmIcon(): string
    {
        return $this->confirmIcon;
    }

    /**
     * @param  string  $confirmIcon
     * @return ResourceAction
     */
    public function setConfirmIcon(string $confirmIcon): ResourceAction
    {
        $this->confirmIcon = $confirmIcon;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmOkType(): string
    {
        return $this->confirmOkType;
    }

    /**
     * @param  string  $confirmOkType
     * @return ResourceAction
     */
    public function setConfirmOkType(string $confirmOkType): ResourceAction
    {
        $this->confirmOkType = $confirmOkType;

        return $this;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param  string  $form
     * @return ResourceAction
     */
    public function setForm(string $form): ResourceAction
    {
        $this->form = $form;

        return $this;
    }
}
