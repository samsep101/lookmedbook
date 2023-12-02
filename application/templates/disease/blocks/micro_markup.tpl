<?php
/**
 * @var DiseaseModel $disease
 */
$authors = $disease->sources;
$authors = preg_replace('/<br \/>/','', $authors);
$authors = preg_replace('/<br\/>/','', $authors);
$authors = strip_tags(html_entity_decode($authors,ENT_COMPAT,'UTF-8'));
?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "publisher": {
            "@type": "Organization",
            "name": "Lookmedbook",
            "logo": {
                "@type": "ImageObject",
                "url": "https://lookmedbook.ru/media/images/home_page/look/header.png"
            }
        },
        "headline": <?= json_encode($disease->title) ?>,
        "datePublished": <?= json_encode($disease->getRFCCreatedAt()) ?>,
        "dateModified": <?= json_encode($disease->getRFCDateUpdated()) ?>,
        "author": <?= json_encode($authors) ?>
    }
</script>
