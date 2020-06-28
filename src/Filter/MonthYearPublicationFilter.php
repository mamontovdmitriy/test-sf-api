<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class MonthYearPublicationFilter extends AbstractContextAwareFilter
{
    public const MONTH = 'month';
    public const YEAR = 'year';

    /**
     * @param string $property
     * @param mixed $value
     * @param QueryBuilder $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param string|null $operationName
     */
    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    )
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass)
            || !in_array($property, [self::MONTH, self::YEAR])
        ) {
            return;
        }

        $requestQuery = $this->requestStack->getCurrentRequest()->query;

        $month = $requestQuery->get(self::MONTH, false);
        $year = $requestQuery->get(self::YEAR, false);

        if (($month && !$year) || (!$month && $year)) {
            throw new BadRequestException('You should use both params "year" and "month", or neither.');
        }

        $param = $queryNameGenerator->generateParameterName($property);

        $queryBuilder
            ->andWhere(sprintf('EXTRACT(%s FROM o.publishedAt) = :%s', $property, $param))
            ->setParameter($param, $value);
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description[$property] = [
                'property' => $property,
                'type'     => 'string',
                'required' => false,
                'swagger'  => [
                    'description' => 'Find articles by year and month of publication',
                    'name'        => 'publication_filter',
                    'type'        => 'filter',
                ],
            ];
        }

        return $description;
    }
}
