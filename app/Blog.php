<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Environment;

class Blog extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id'];
    protected $appends = ['markdown_content'];

    private $_DELETED_TITLE = 'Removed blog.';
    private $_DELETED_TEXT = '_The blog has been removed._';
    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function community()
    {
        return $this->belongsTo('App\Community');
    }

    public function getMarkdownContentAttribute()
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $commonMark = new CommonMarkConverter(
            ['html_input' => 'strip', 'allow_unsafe_links' => false],
            $environment
        );
        $content = $this->attributes['content'] ? $this->attributes['content'] : '';
        $content = $this->getContentAttribute($content);
        return $commonMark->convertToHtml($content);
    }

    public function getContentAttribute($value)
    {
        if ($this->attributes['deleted_at']) {
            return $this->_DELETED_TEXT;
        } else {
            return $value;
        }
    }

    public function getTitleAttribute($value)
    {
        if ($this->attributes['deleted_at']) {
            return $this->_DELETED_TITLE;
        } else {
            return $value;
        }
    }
}
