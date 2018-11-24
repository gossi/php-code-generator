<?php
declare(strict_types=1);

namespace gossi\codegen\generator\builder;

use gossi\codegen\model\AbstractModel;
use gossi\codegen\generator\builder\parts\ValueBuilderPart;

class ConstantBuilder extends AbstractBuilder {

	use ValueBuilderPart;

	public function build(AbstractModel $model): void {
		$this->buildDocblock($model);
		$this->writer->write('const ' . $model->getName() . ' = ');
		$this->writeValue($model);
		$this->writer->writeln(';');
	}

}