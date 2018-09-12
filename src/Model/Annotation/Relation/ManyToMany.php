<?php
namespace Imi\Model\Annotation\Relation;

use Imi\Bean\Annotation\Base;
use Imi\Bean\Annotation\Parser;

/**
 * 多对多关联
 * @Annotation
 * @Target("PROPERTY")
 * @Parser("Imi\Model\Parser\RelationParser")
 */
class ManyToMany extends Base
{
	/**
	 * 关联的模型类
	 * 可以是包含命名空间的完整类名
	 * 可以同命名空间下的类名
	 *
	 * @var string
	 */
	public $model;

	/**
	 * 中间表模型
	 * 可以是包含命名空间的完整类名
	 * 可以同命名空间下的类名
	 *
	 * @var string
	 */
	public $middle;

	/**
	 * 属性名，赋值为关联的模型对象列表
	 *
	 * @var string
	 */
	public $rightMany;
}