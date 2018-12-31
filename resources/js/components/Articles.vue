<template>
    <div id="articles">
        <h2>Articles</h2>
        <form  @submit.prevent="addArticle" class="mb-2">
            <div class="form-group">
                <label for="title">Title :</label>
                <input type="text" name="title" class="form-control" id="title" v-model="article.title">
            </div>
            <div class="form-group">
                <label for="body">Body :</label>
                <textarea name="body" id="body" cols="30" rows="10" class="form-control" v-model="article.body"></textarea>
            </div>
            <input type="submit" value="Save" class="btn btn-primary btn-block">
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled : !pagination.prev_page_url }">
                    <a class="page-link" href="#" @click="fetchArticles(pagination.prev_page_url)">
                        Previous
                    </a>
                </li>
                <li class="page-item disabled text-dark">
                    <a class="page-link" href="#">
                        Page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </a>
                </li>
                <li class="page-item"  :class="{ disabled : !pagination.next_page_url }">
                    <a class="page-link" href="#"  @click="fetchArticles(pagination.next_page_url)">
                        Next
                    </a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="article in articles" :key="article.id">
            <h4>{{ article.title }}</h4>
            <p>{{ article.body }}</p>
            <button class="btn btn-warning mb-2" @click="editArticle(article)">Edit</button>
            <button class="btn btn-danger" @click="deleteArticle(article.id)">Delete</button>
        </div>
    </div>
</template>

<script>
    export default {
       data () {
           return {
               articles: [],
               article: {
                   id: '',
                   title: '',
                   body: ''
               },
               article_id: '',
               pagination: '',
               edit: false,
           }
       },
       created() {
           this.fetchArticles();
       },
       methods: {
           fetchArticles: function(page_url) {
                page_url = page_url || '/api/articles';
                axios.get(page_url)
                .then((response) => {
                    this.articles = response.data.data;
                    this.makePagination(response.data.meta, response.data.links);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
           },
           makePagination: function(meta, links) {
               let pagination = {
                   current_page: meta.current_page,
                   last_page: meta.last_page,
                   next_page_url: links.next,
                   prev_page_url: links.prev
               }
               this.pagination = pagination;
           },
           deleteArticle: function(articleId) {
               if (confirm('Are you sure?')) {
                   axios.delete('/api/articles/'+articleId).then((res) => {
                       alert('Article removed');
                       this.fetchArticles();
                   })
                   .catch(function (error){
                       console.log(error);
                   });
               }
           },
           addArticle: function() {
               if (this.edit === false) {
                   //add article
                   axios.post('/api/articles', this.article).then((res) => {
                       this.article.title = '';
                       this.article.body = '';
                       alert('Article added successfully!');
                       this.fetchArticles();
                   })
                   .catch(function(error){
                       console.log(error);
                   });
               } else {
                   //update article
                   axios.put('/api/articles/'+this.article_id, this.article).then((res) => {
                       this.article.title = '';
                       this.article.body = '';
                       alert('Article updated successfully!');
                       this.fetchArticles();
                   })
                   .catch(function(error){
                       console.log(error);
                   });
               }
           },
           editArticle: function(article) {
               this.edit = true;
               this.article.id = article.id;
               this.article_id = article.id;
               this.article.title = article.title;
               this.article.body = article.body;
           }
       }
    }
</script>
