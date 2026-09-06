export default async function CategoryPage({ params, searchParams }) {
  const { slug } = await params;
  const filters = await searchParams;

  console.log("CATEGORY SLUG:", slug);
  console.log("FILTERS:", filters);

  // گرفتن محصولات دسته‌بندی

  return (
    <div>
      ...
    </div>
  );
}