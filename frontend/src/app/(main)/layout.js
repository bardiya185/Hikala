import Header from "@/components/templates/header";
import Footer from "@/components/templates/footer/Footer";

export default function MainLayout({ children }) {
  return (
    <>
      <Header />

      {children}

      <Footer />
    </>
  );
}