<?php

namespace App\Controller\Admin;

use App\Entity\Driver;
use App\Entity\Team;
use App\Entity\Season;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


class DriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Driver::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('fullDriverName', 'Nombre del Piloto'),

            // Configurar los campos de imagen para las fotos del logo y el coche
            ImageField::new('urlDriverPhoto', 'Foto del Piloto')
                ->setBasePath('uploads/driver_photo')  // Ruta base para acceder a las imágenes
                ->setUploadDir('public/uploads/driver_photo')  // Ruta donde se almacenarán las imágenes en el servidor
                ->setRequired(false)
                ->setSortable(false), // Evita ordenar por este campo

            TextField::new('country', 'País de Nacimiento')->setRequired(false),
            IntegerField::new('racingNumber', 'Dorsal')->setRequired(false),

            // Configurar el campo Team como una relación
            AssociationField::new('teamId', 'Escudería')
                ->setFormTypeOption('choice_label', 'fullNameTeam'),


            // Configurar el campo Season como una relación
            AssociationField::new('season', 'Temporada')
                ->setFormTypeOption('choice_label', 'seasonName')
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['fullDriverName' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setPageTitle('new', 'Añadir piloto') // Para la página "New"
            ->setPageTitle('edit', 'Editar piloto') // Para la página "Edit"
            ->setPageTitle('detail', 'Detalle piloto') // Para la página "detail"
            ->setEntityLabelInSingular('Piloto')   // Etiqueta singular
            ->setEntityLabelInPlural('Pilotos');   // Etiqueta plural
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Botones en la página INDEX
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Añadir nuevo piloto');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Editar');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Eliminar');
            })

            // Botones en la página NEW
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setLabel('Guardar y añadir otra');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Guardar y volver');
            })

            // Botones en la página EDIT
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Guardar y volver');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setLabel('Guardar y continuar');
            });
    }

}
