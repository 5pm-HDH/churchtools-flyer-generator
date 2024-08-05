<?php

namespace App\Models;

class PreInstalledTemplateDTO implements \JsonSerializable
{

    public readonly string $id;

    public readonly  string $templateName;

    public readonly  string|null $imgSrc;

    public readonly  string $templatePublicSrc;

    public readonly  string $templateAbsoluteSrc;


    /**
     * @param string $id
     * @param string $templateName
     * @param string|null $imgSrc public path where user can retrieve image
     * @param string $templateSrc relative path to template from project root (e.q. /public/templates/Template.docx)
     */
    public function __construct(string $id, string $templateName, string|null $imgSrc, string $templateSrc)
    {
        $this->id = $id;
        $this->templateName = $templateName;
        $this->imgSrc = $imgSrc;
        $this->templateAbsoluteSrc = base_path($templateSrc);
        $this->templatePublicSrc = url(str_replace("public".DIRECTORY_SEPARATOR, "", $templateSrc));
    }


    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->templateName,
            "imgSrc" => $this->imgSrc,
            "templateSrc" => $this->templatePublicSrc
        ];
    }


    /**
     * @return PreInstalledTemplateDTO[]
     */
    public static function getAll(): array
    {
        return [
            new PreInstalledTemplateDTO(1, "Flyer Minimalist", url("/templates/TemplateMinimalist-Image.png"), "public".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."TemplateMinimalist.docx"),
            new PreInstalledTemplateDTO(2, "Flyer Cave", url("/templates/TemplateCave-Image.png"), "public".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."TemplateCave.docx"),
            new PreInstalledTemplateDTO(3, "Flyer Event", url("/templates/TemplateEvent-Image.png"), "public".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."TemplateEvent.docx"),
        ];
    }

    public static function find(string $id): ?PreInstalledTemplateDTO
    {
        $templates = self::getAll();
        foreach ($templates as $template) {
            if($template->id === $id){
                return $template;
            }
        }
        return null;
    }
}
