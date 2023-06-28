<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Collections;

use Illuminate\Support\Arr;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Hub\Http\Livewire\Components\Collections\CollectionGroupShow as LunarCollectionGroupShow;
use Lunar\Models\Attribute;
use Lunar\Models\Collection;
use Lunar\Models\Language;

class CollectionGroupShow extends LunarCollectionGroupShow
{
    /**
     * Create the new collection.
     *
     * @return void
     */
    public function createCollection()
    {
        dd('createCollection');
        $rules = Arr::only($this->rules(), ['collection.name', 'slug']);

        $this->validate($rules, [
            'collection.name.required' => __('adminhub::validation.generic_required'),
        ]);

        $attribute = Attribute::whereHandle('name')->whereAttributeType(Collection::class)->first();

        $attributeType = $attribute?->type ?: TranslatedText::class;

        $name = $this->collection['name'];

        if ($attributeType == TranslatedText::class) {
            $name = [
                $this->defaultLanguage => $this->collection['name'],
            ];
        }

        $collection = Collection::create([
            'collection_group_id' => $this->group->id,
            'attribute_data' => collect([
                'name' => new $attributeType($name),
            ]),
        ], $this->collectionParent);

        $collection->setParentId($this->collectionParent->id)->save();

        if ($this->slug) {
            $collection->urls()->create([
                'slug' => $this->slug,
                'default' => true,
                'language_id' => Language::getDefault()->id,
            ]);
        }

        $this->collection = null;
        $this->slug = null;

        $this->showCreateForm = false;

        $this->loadTree();

        $this->emit('refreshTree', $this->tree);

        $this->notify(
            __('adminhub::notifications.collections.added')
        );
    }
}
