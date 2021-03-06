<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUiBundle\ParamConverter;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\ContentTypeDraft;
use eZ\Publish\Core\Persistence\Legacy\Exception\TypeNotFound;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentTypeDraftParamConverter implements ParamConverterInterface
{
    const PARAMETER_CONTENT_TYPE_ID = 'contentTypeId';

    /** @var ContentTypeService */
    private $contentTypeService;

    /**
     * ContentTypeGroupParamConverter constructor.
     *
     * @param ContentTypeService $contentTypeGroupService
     */
    public function __construct(ContentTypeService $contentTypeGroupService)
    {
        $this->contentTypeService = $contentTypeGroupService;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $id = (int)$request->get(self::PARAMETER_CONTENT_TYPE_ID);

        try {
            $contentTypeDraft = $this->contentTypeService->loadContentTypeDraft($id);
        } catch (TypeNotFound $e) {
            $contentType = $this->contentTypeService->loadContentType($id);
            $contentTypeDraft = $this->contentTypeService->createContentTypeDraft($contentType);
        }

        if (!$contentTypeDraft) {
            throw new NotFoundHttpException("ContentTypeDraft $id not found!");
        }

        $request->attributes->set($configuration->getName(), $contentTypeDraft);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return ContentTypeDraft::class === $configuration->getClass();
    }
}
