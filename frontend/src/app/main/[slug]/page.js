export default async function MainCategoryPage({ params }) {
  const { slug } = await params;

  console.log("MAIN CATEGORY:", slug);

  return (
    <div>
      <h1>{slug}</h1>
    </div>
  );
}