<?php

namespace spec\Grandadevans\GenerateForm\Handlers;

use Grandadevans\GenerateForm\Helpers\Sanitizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;


class PathHandlerSpec extends ObjectBehavior
{
    public function it_creates_the_correct_directory_when_namespace_given_but_no_dir(Sanitizer $sanitizer)
    {
        $sanitizer->convertNamespaceToPath('This\\Is\\The\\Test\\Namespace')->willReturn('This/Is/The/Test/Namespace');

        $details = [
            'namespace' => 'This\\Is\\The\\Test\\Namespace',
            'dir' => '',
            'className' => 'FooBar'
        ];

        $this->getDirectory($details, $sanitizer)->shouldReturn('This/Is/The/Test/Namespace');
    }

    public function it_creates_the_correct_directory_when_directory_given_and_no_namespace(Sanitizer $sanitizer)
    {
        $details = [
            'namespace' => 'This\\Is\\The\\Test\\Namespace',
            'dir' => 'This/Is/Another/Test/Directory',
            'className' => 'FooBar'
        ];

        $this->getDirectory($details, $sanitizer)->shouldReturn('This/Is/Another/Test/Directory');
    }


    public function it_creates_the_correct_directory_both_directory_and_namespace_are_provided(Sanitizer $sanitizer)
    {
        $details = [
            'namespace' => 'This\\Is\\The\\Test\\Namespace',
            'dir' => 'This/Is/Yet/Another/Test/Directory',
            'className' => 'FooBar'
        ];

        $this->getDirectory($details, $sanitizer)->shouldReturn('This/Is/Yet/Another/Test/Directory');
    }

    public function it_creates_the_correct_directory_when_neither_directory_nor_namespace_are_provided(Sanitizer $sanitizer)
    {
        $details = [
            'namespace' => '',
            'dir' => '',
            'className' => 'FooBar'
        ];

        $this->getDirectory($details, $sanitizer)->shouldReturn('app/Forms');
    }

    public function it_constructs_the_correct_filename()
    {
        $details = [
            'namespace' => 'This\\Is\\The\\Test\\Namespace',
            'dir' => 'This/Is/Yet/Another/Test/Directory',
            'className' => 'FooBar'
        ];

        $this->getFileName($details)->shouldReturn('FooBarForm.php');
    }

    public function it_returns_true_when_testing_that_a_valid_file_exists(Filesystem $filesystem)
    {
        $filename = __DIR__ . '/' . __FILE__ ;

        $filesystem->exists($filename)->willReturn(true);

        $this->doesPathExist($filename, $filesystem)->shouldReturn(true);
    }


    public function it_returns_false_when_testing_that_an_INvalid_file_exists(Filesystem $filesystem)
    {
        $filename = __DIR__ . '/' . __FILE__ . 'extra_bit_that_doesnt_Really_exist';

        $filesystem->exists($filename)->willReturn(false);

        $this->doesPathExist($filename, $filesystem)->shouldReturn(false);
    }

    public function it_tries_to_create_a_non_existent_directory(Filesystem $filesystem)
    {
        $dir = __DIR__ . 'a_bit_extra';

        $filesystem->isDirectory($dir)->willReturn(false);

        $filesystem->makeDirectory($dir, 0755, true)->shouldBeCalled()->willReturn(true);

        $this->createMissingDirectory($dir, $filesystem)->shouldReturn(true);
    }


    public function it_doesnt_try_to_create_an_already_existing_directory(Filesystem $filesystem)
    {
        $dir = __DIR__;

        $filesystem->isDirectory($dir)->willReturn(true);

        $this->makeSureFinalDirectoryExist($dir, $filesystem)->shouldReturn(true);
    }

    public function it_gets_the_full_form_path(Sanitizer $sanitizer)
    {
        define('DS', DIRECTORY_SEPARATOR);

        $details = [
            'namespace' => 'This\\Is\\The\\Test\\Namespace',
            'dir' => 'This/Is/Yet/Another/Test/Directory',
            'className' => 'FooBar'
        ];

        $sanitizer->stripDoubleDirectorySeparators('This/Is/Yet/Another/Test/Directory/FooBarForm.php')
            ->willReturn('This/Is/Yet/Another/Test/Directory/FooBarForm.php');

        $this->getFullPath($sanitizer, $details)->shouldReturn('This/Is/Yet/Another/Test/Directory/FooBarForm.php');
    }
}
