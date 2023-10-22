<template>
  <div class="container">
    <div class="d-flex justify-content-evenly">
      <select
        v-model="searchType"
        class="form-select search-select"
        aria-label="Default select example"
      >
        <option value="name">
          Name
        </option>
        <option value="family">
          Family
        </option>
      </select>
      <div class="input-group">
        <input
          v-model="searchValue"
          type="text"
          class="form-control search-field"
          :placeholder="`Search by ${searchType}`"
          aria-describedby="button-addon2"
          @keyup.enter="searchFruit"
        >
        <button
          id="button-addon2"
          class="btn btn-outline-secondary"
          type="button"
          @click="searchFruit"
        >
          Search
        </button>
      </div>
      <button
        type="button"
        class="btn btn-primary position-relative btn-sm"
        @click="goToFavorites"
      >
        Favorites
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          {{ favoritesCount }}
          <span class="visually-hidden">Favorite fruits</span>
        </span>
      </button>
    </div>
    <table class="table table-hover mt-4">
      <thead>
        <tr>
          <th scope="col">
            ID
          </th>
          <th scope="col">
            Name
          </th>
          <th scope="col">
            Family
          </th>
          <th scope="col">
            Order
          </th>
          <th scope="col">
            Nutritions
          </th>
          <th scope="col">
            Action
          </th>
        </tr>
      </thead>
      <tbody v-if="fetching">
        <tr>
          <td colspan="6">
            <div class="text-center">
              <div
                class="spinner-border"
                role="status"
              >
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr
          v-for="fruit in fruits"
          :key="`favorite-fruit-${fruit.id}`"
        >
          <th scope="row">
            {{ fruit.id }}
          </th>
          <td>{{ fruit.name }}</td>
          <td>{{ fruit.family }}</td>
          <td>{{ fruit.fruitOrder }}</td>
          <td>
            <table class="table table-borderless">
              <tbody>
                <tr class="nutrition-row">
                  <td class="nutrition-cell">
                    <strong>Fat: </strong>{{ fruit.nutritions.fat }}
                  </td>
                  <td class="nutrition-cell">
                    <strong>Sugar: </strong>{{ fruit.nutritions.sugar }}
                  </td>
                  <td class="nutrition-cell">
                    <strong>Protein: </strong>{{ fruit.nutritions.protein }}
                  </td>
                  <td class="nutrition-cell">
                    <strong>Calories: </strong>{{ fruit.nutritions.calories }}
                  </td>
                  <td class="nutrition-cell">
                    <strong>Carbohydrates: </strong>{{ fruit.nutritions.carbohydrates }}
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td>
            <span
              v-if="fruit?.isFavourite"
              class="badge rounded-pill bg-light text-dark"
            >
              Added to favorites
            </span>
            <button
              v-else
              :disabled="favoritesCount === 10"
              type="button"
              class="btn btn-sm btn-outline-primary"
              @click="addToFavorite(fruit.id)"
            >
              Add to favourites
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <nav
      aria-label="navigation"
      class="pb-4"
    >
      <ul class="pagination">
        <li
          class="page-item"
          :class="{disabled: parseInt(currentPage) === 1 || pages.length <= 1}"
        >
          <a
            class="page-link"
            href="#"
            aria-label="First"
            @click="navigatePage(1)"
          >
            <span aria-hidden="true">&laquo;&laquo;</span>
          </a>
        </li>
        <li
          class="page-item"
          :class="{disabled: parseInt(currentPage) === 1 || pages.length <= 1}"
        >
          <a
            class="page-link"
            href="#"
            aria-label="Previous"
            @click="navigatePage('previous')"
          >
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li
          v-for="pageNumber in pages"
          :key="`page-${pageNumber}`"
          :class="{ active: parseInt(currentPage) === parseInt(pageNumber) }"
          class="page-item"
        >
          <a
            class="page-link"
            href="#"
            @click="navigatePage(pageNumber)"
          >{{ pageNumber }}</a>
        </li>
        <li
          class="page-item"
          :class="{disabled: parseInt(currentPage) === parseInt(lastPage) || pages.length <= 1}"
        >
          <a
            class="page-link"
            href="#"
            aria-label="Next"
            @click="navigatePage('next')"
          >
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
        <li
          class="page-item"
          :class="{disabled: parseInt(currentPage) === parseInt(lastPage) || pages.length <= 1}"
        >
          <a
            class="page-link"
            href="#"
            aria-label="Last"
            @click="navigatePage('last')"
          >
            <span aria-hidden="true">&raquo;&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>
<script>
import axios from 'axios'

function getPageparam(stringUrl)
{
  const searhParam = new URLSearchParams(stringUrl);
  return searhParam.get('page');
}

export default {
  name: 'FruitList',
  data() {
    return {
      fetching: true,
      fruits: [],
      favoritesCount: 0,
      searchType: 'name',
      searchValue: '',
      currentPage: 1,
      lastPage: 1,
      previous: 1,
      next: 1,
      pages: [],
    }
  },
  created () {
    this.getFavoritesCount();
    this.getFruits();
  },
  methods: {
    async getFruits(page, searchQuery) {
      this.fetching = true;
      const navigatePage = page || 1;
      let fetchUrl = `/api/fruits?page=${navigatePage}&itemsPerPage=10`;
      if (searchQuery) {
        const searchParam = `&${this.searchType}=${searchQuery}`;
        fetchUrl = fetchUrl + searchParam;
      } else if (this.searchValue) {
        const searchParam = `&${this.searchType}=${this.searchValue}`;
        fetchUrl = fetchUrl + searchParam;
      }

      const res = await axios.get(fetchUrl);
      this.fruits = res.data['hydra:member'];
      if (res?.data['hydra:totalItems'] > 0) {
        this.currentPage = 1;
        this.lastPage = 1;
        this.previous = 1;
        this.next = 1;
        this.pages = [];

        const paginationDetails = res.data['hydra:view'];

        this.currentPage = getPageparam(paginationDetails['@id']);
        if (paginationDetails['hydra:last']) {
          this.lastPage = getPageparam(paginationDetails['hydra:last']);
        }

        if (paginationDetails['hydra:previous']) {
          this.previous = getPageparam(paginationDetails['hydra:previous']);
        }

        if (paginationDetails['hydra:next']) {
          this.next = getPageparam(paginationDetails['hydra:next']);
        }

        if (this.lastPage > 1) {
          let pageCounter = 1;
          this.pages = [];
          for (let i = this.currentPage - 1; i <= this.lastPage; i++) {
            if (i <= 0) {
              continue;
            }

            if (pageCounter === 5) {
              break;
            }

            this.pages.push(i);
            pageCounter += 1;
          }
        }
      }

      this.fetching = false;
    },

    async getFavoritesCount() {
      const res = await axios.get('/api/fruits?page=1&itemsPerPage=1&isFavourite=1');
      if (res?.data['hydra:totalItems']) {
        this.favoritesCount = res.data['hydra:totalItems'];
      }
    },

    searchFruit() {
      this.getFruits(1, this.searchValue);
    },

    navigatePage(pageParam) {
      let navigatePage = 1;

      switch (pageParam) {
        case 'next':
          navigatePage = this.next;
          break;
        case 'previous':
          navigatePage = this.previous;
          break;
        case 'last':
          navigatePage = this.lastPage;
          break;
        default:
          navigatePage = pageParam;
          break;
      }

      this.getFruits(navigatePage);
    },

    goToFavorites() {
      location.href = '/favorites';
    },

    async addToFavorite(fruitId) {
      const res = await axios.put(`/api/fruits/${fruitId}`, { isFavourite: true });
      this.getFavoritesCount();
      this.getFruits();
    }
  },
}
</script>
<style scoped>
.nutrition-row {
  font-size: 12px;
}
.nutrition-cell {
  padding: 0.2rem;
}
.search-field {
  max-width: 400px;
}
.search-select {
  max-width: 100px;
}
</style>