<?php

namespace spec\Grandadevans\GenerateForm\BuilderClasses;

use Illuminate\Filesystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mustache_Engine;
use Mockery as m;


class OutputBuilderSpec extends ObjectBehavior
{

	public function let(Filesystem $filesystem, Mustache_Engine $mustache_Engine)
	{
		$mustache_Engine
			->render("template_contents", [
				"rules" => [],
				"namespace" => "grandadevans",
				"className" => "FooBar"
			])
			->willReturn('Rendered Template Contents');

		$path = __DIR__ . '/../Templates/GenerateFormTemplate.stub';
		$path = str_replace('tests/spec', 'src', $path);

		$filesystem->get($path)->willReturn('template_contents');
		$filesystem->put("tests/Forms/FooBarForm.php", "Rendered Template Contents")->willReturn([
            'status' => 'success',
            'path' => "tests/Forms/FooBarForm.php"
        ]);


	}

	public function it_tests_the_whole_output_system(Mustache_Engine $mustache_Engine, Filesystem $filesystem)
	{
        $path = "tests/Forms/FooBarForm.php";

        $renderedContents = "Rendered Template Contents";

        $filesystem->put($path, $renderedContents)->willReturn(true);

        $this->writeTemplate($filesystem, $renderedContents, $path)->shouldReturn([
            'status' => 'success',
            'path' => $path
]       );

		$this->build(
			$mustache_Engine,
			$filesystem,
			[
				'rules' => [],
				'className' => 'FooBar',
				'namespace' => 'grandadevans'
			],
			'tests/Forms/FooBarForm.php');

    }


	public function it_returns_the_correct_template_contents(Filesystem $filesystem)
	{
		$this->getTemplateContents($filesystem)->shouldReturn('template_contents');
	}


	public function it_renders_the_mustache_template(Filesystem $filesystem, Mustache_Engine $mustache_Engine)
	{
		$args = [
			'rules' => [],
			'className' => 'FooBar',
			'namespace' => 'grandadevans'
		];

		$path = "tests/Forms/FooBarForm.php";

		$this->renderTemplate($mustache_Engine, $args, $filesystem)->shouldReturn('Rendered Template Contents');
	}


	public function it_writes_the_correct_output_to_disk(Filesystem $filesystem)
	{
		$path = "tests/Forms/FooBarForm.php";

		$renderedContents = "Rendered Template Contents";

		$filesystem->put($path, $renderedContents)->willReturn(true);

		$this->writeTemplate($filesystem, $renderedContents, $path)->shouldReturn([
            'status' => 'success',
            'path' => $path
        ]);
	}


	public function it_returns_the_correct_information_on_failing_to_write_to_disk(Filesystem $filesystem)
	{
		$path = "tests/Forms/FooBarForm.php";

		$renderedContents = "Rendered Template Contents";

		$filesystem->put($path, $renderedContents)->willReturn(false);

		$this->writeTemplate($filesystem, $renderedContents, $path)->shouldReturn([
			                                                                          'status' => 'fail',
			                                                                          'path' => $path
		                                                                          ]);
	}

}
