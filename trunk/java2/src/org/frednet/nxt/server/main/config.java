package org.frednet.nxt.server.main;

import java.io.File;
import org.w3c.dom.Document;
import org.w3c.dom.*;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

public class config {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		try {
			// just demo data
			NXTportRange[0] = 1;
			NXTportRange[1] = 50;
			//read config.xml
			DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory
					.newInstance();
			DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
			Document doc = docBuilder.parse(new File("config.xml"));

			// normalize text representation
			doc.getDocumentElement().normalize();
			NodeList nxtconfig = doc.getElementsByTagName("nxt-config/item");
			int totalItem = nxtconfig.getLength();
            System.out.println("Total no of people : " + totalItem);
			
		} catch (SAXParseException err) {
			System.out.println("** Parsing error" + ", line "
					+ err.getLineNumber() + ", uri " + err.getSystemId());
			System.out.println(" " + err.getMessage());

		} catch (SAXException e) {
			Exception x = e.getException();
			((x == null) ? e : x).printStackTrace();

		} catch (Throwable t) {
			t.printStackTrace();
		}
	}

	public static int NXTportRange[] = { 1, 50 };

}
