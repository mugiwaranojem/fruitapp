<template>
  <div class="container">
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
          :key="`fruit-${fruit.id}`"
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
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">
            <div class="mb-3 fw-bold">
              Total Nutritions:
            </div>
            <ol class="list-group list-group-numbered">
              <li
                v-for="nutrition in Object.keys(totalNutritions)"
                :key="`nutrition-${nutrition}`"
                class="list-group-item d-flex justify-content-between align-items-start"
              >
                <div class="ms-2 me-auto">
                  <div>
                    Total <span class="text-capitalize">{{ nutrition }}</span>
                  </div>
                </div>
                <span class="badge bg-primary rounded-pill">{{ totalNutritions[nutrition].toFixed(2) }}</span>
              </li>
            </ol>
          </td>
        </tr>
      </tfoot>
    </table>
    <a href="/"><span aria-hidden="true">&laquo;&laquo;</span>&nbsp;Back</a>
  </div>
</template>
<script>
import axios from 'axios'

export default {
  name: 'ListFavorite',
  data() {
    return {
      fetching: true,
      fruits: [],
    }
  },
  computed: {
    totalNutritions() {
      const nutritions = {
        fat: 0,
        sugar: 0,
        protein: 0,
        calories: 0,
        carbohydrates: 0
      };

      if (this.fruits.length) {
        this.fruits.forEach(fruit => {
          Object.keys(fruit.nutritions).forEach(nutritionKey => {
            if (typeof nutritions[nutritionKey] === 'number' && typeof fruit.nutritions[nutritionKey] === 'number') {
              nutritions[nutritionKey] = nutritions[nutritionKey] + fruit.nutritions[nutritionKey];
            }
          })
        });
      }

      return nutritions;
    }
  },
  created () {
    this.getFruits();
  },
  methods: {
    async getFruits() {
      this.fetching = true;
      let fetchUrl = `/api/fruits?page=1&itemsPerPage=10&isFavourite=1`;
      const res = await axios.get(fetchUrl);
      this.fruits = res.data['hydra:member'];
      if (res?.data['hydra:totalItems'] > 0) {
      }
      this.fetching = false;
    },
  }
}
</script>
<style scoped>
.nutrition-row {
  font-size: 12px;
}
</style>